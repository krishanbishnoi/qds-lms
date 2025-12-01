<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class NewDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard page
     */
    public function index()
    {
        // Fetch filter lists
        // support both `agencies` and `partners` table names
        $agencyTable = Schema::hasTable('agencies') ? 'agencies' : (Schema::hasTable('partners') ? 'partners' : null);
        if ($agencyTable) {
            $agencies = DB::table($agencyTable)->select('id', 'name')->orderBy('name')->get();
        } else {
            $agencies = collect();
        }

        // regions table might use column 'region' or 'name'
        if (Schema::hasTable('regions')) {
            $regionNameCol = Schema::hasColumn('regions', 'region') ? 'region' : 'name';
            $regions = DB::table('regions')->select('id', $regionNameCol)->orderBy($regionNameCol)->get();
        } else {
            $regions = collect();
        }

        $states = Schema::hasTable('states') ? DB::table('states')->select('id', Schema::hasColumn('states', 'name') ? 'name' : 'state')->orderBy(Schema::hasColumn('states', 'name') ? 'name' : 'state')->get() : collect();
        // trainings table may use 'title' column; try to alias to 'name' for views
        try {
            $trainings = DB::table('trainings')->select('id', DB::raw('title as name'))->orderBy('title')->limit(200)->get();
        } catch (\Exception $e) {
            Log::warning('Trainings title->name query failed: ' . $e->getMessage());
            // fallback: try selecting 'name' directly if present
            try {
                $trainings = DB::table('trainings')->select('id', 'name')->orderBy('name')->limit(200)->get();
            } catch (\Exception $e2) {
                // final fallback: select id and title without ordering
                Log::warning('Trainings name fallback failed: ' . $e2->getMessage());
                $trainings = DB::table('trainings')->select('id', Schema::hasColumn('trainings', 'title') ? DB::raw('title as name') : 'id')->limit(200)->get();
            }
        }

        return view('admin.training_dashboard', compact('agencies', 'regions', 'states', 'trainings'));
    }

    /**
     * Return aggregated dashboard stats JSON (used by AJAX)
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $filters = $this->buildFilters($request);

            // cache key based on filters
            $cacheKey = 'dashboard_stats:' . md5(json_encode($filters));

            $data = Cache::remember($cacheKey, 60, function () use ($filters) {
                // Get training IDs matching filters
                // detect pivot/participants table name (common variants)
                $pivotCandidates = ['training_participants', 'training_participants', 'training_participant', 'training_partiviptant', 'training_participent', 'training_participantss'];
                $pivot = null;
                foreach ($pivotCandidates as $cand) {
                    if (Schema::hasTable($cand)) { $pivot = $cand; break; }
                }

                // detect start/end date columns (some installs use start_date_time/end_date_time)
                $startCol = Schema::hasColumn('trainings', 'start_date_time') ? 'start_date_time' : (Schema::hasColumn('trainings', 'start_date') ? 'start_date' : null);
                $endCol = Schema::hasColumn('trainings', 'end_date_time') ? 'end_date_time' : (Schema::hasColumn('trainings', 'end_date') ? 'end_date' : null);

                // detect agency table name
                $agencyTable = Schema::hasTable('agencies') ? 'agencies' : (Schema::hasTable('partners') ? 'partners' : null);

                $trainingQuery = DB::table('trainings as t')
                    ->when($filters['training_id'], function ($q) use ($filters) {
                        $q->where('t.id', $filters['training_id']);
                    })
                    ->when($filters['from_date'], function ($q) use ($filters, $startCol) {
                        if ($startCol) {
                            $q->whereDate("t.{$startCol}", '>=', $filters['from_date']);
                        }
                    })
                    ->when($filters['to_date'], function ($q) use ($filters, $endCol) {
                        if ($endCol) {
                            $q->whereDate("t.{$endCol}", '<=', $filters['to_date']);
                        }
                    })
                    ->when($filters['agency_id'], function ($q) use ($filters, $pivot) {
                        if (Schema::hasColumn('trainings', 'agency_id')) {
                            $q->where('t.agency_id', $filters['agency_id']);
                        } elseif ($pivot) {
                            $q->whereExists(function ($sub) use ($filters, $pivot) {
                                $sub->select(DB::raw(1))
                                    ->from("{$pivot} as p")
                                    ->join('users as u', 'p.trainee_id', '=', 'u.id')
                                    ->whereRaw('p.training_id = t.id')
                                    ->where('u.agency_id', $filters['agency_id']);
                            });
                        }
                    })
                    ->when($filters['region_id'], function ($q) use ($filters, $pivot) {
                        if (Schema::hasColumn('trainings', 'region_id')) {
                            $q->where('t.region_id', $filters['region_id']);
                        } elseif ($pivot) {
                            $q->whereExists(function ($sub) use ($filters, $pivot) {
                                $sub->select(DB::raw(1))->from("{$pivot} as p")->join('users as u', 'p.trainee_id', '=', 'u.id')->whereRaw('p.training_id = t.id')->where('u.region_id', $filters['region_id']);
                            });
                        }
                    })
                    ->when($filters['state_id'], function ($q) use ($filters, $pivot) {
                        if (Schema::hasColumn('trainings', 'state_id')) {
                            $q->where('t.state_id', $filters['state_id']);
                        } elseif ($pivot) {
                            $q->whereExists(function ($sub) use ($filters, $pivot) {
                                $sub->select(DB::raw(1))->from("{$pivot} as p")->join('users as u', 'p.trainee_id', '=', 'u.id')->whereRaw('p.training_id = t.id')->where('u.state_id', $filters['state_id']);
                            });
                        }
                    });

                // pluck plain 'id' (avoid table alias in pluck)
                $trainingIds = $trainingQuery->pluck('id');

                Log::debug('Dashboard: trainingIds count=' . ($trainingIds ? $trainingIds->count() : 0) . ' pivot=' . ($pivot ?: 'none') . ' agencyTable=' . ($agencyTable ?: 'none'));

                if ($trainingIds->isEmpty()) {
                    return $this->emptyStats();
                }

                    $today = date('Y-m-d');

                    // Basic counts
                    $totalTrainings = $trainingIds->count();

                    // Check schema for optional columns/tables
                    $hasStartDate = $startCol ? true : false;
                    $hasEndDate = $endCol ? true : false;
                    $hasRegion = Schema::hasColumn('trainings', 'region_id');
                    $hasState = Schema::hasColumn('trainings', 'state_id');
                    $hasAgencyCol = Schema::hasColumn('trainings', 'agency_id');
                    $hasPivot = $pivot ? true : false;

                    if ($hasStartDate && $hasEndDate) {
                        $upcoming = DB::table('trainings')->whereIn('id', $trainingIds)->whereDate('start_date_time', '>', $today)->count();
                        $ongoing = DB::table('trainings')->whereIn('id', $trainingIds)->whereDate('start_date_time', '<=', $today)->whereDate('end_date_time', '>=', $today)->count();
                        $completed = DB::table('trainings')->whereIn('id', $trainingIds)->whereDate('end_date_time', '<', $today)->count();
                    } else {
                        Log::warning('Dashboard: trainings missing start_date_time/end_date columns; skipping date-based counts');
                        $upcoming = $ongoing = $completed = 0;
                    }

                    // Users allocations & statuses — require pivot table
                    if ($hasPivot) {
                        $totalAssigned = (int) DB::table('training_participants')->whereIn('training_id', $trainingIds)->distinct('trainee_id')->count('trainee_id');

                        $completedUsers = (int) DB::table('training_participants')->whereIn('training_id', $trainingIds)->where('status', 'completed')->distinct('trainee_id')->count('trainee_id');
                        $inprogressUsers = (int) DB::table('training_participants')->whereIn('training_id', $trainingIds)->where('status', 'in-progress')->distinct('trainee_id')->count('trainee_id');
                        $notStartedUsers = max(0, $totalAssigned - $completedUsers - $inprogressUsers);
                    } else {
                        Log::warning('Dashboard: pivot table training_participants missing; skipping user allocation counts');
                        $totalAssigned = $completedUsers = $inprogressUsers = $notStartedUsers = 0;
                    }

                    $globalCompletionRate = $totalAssigned > 0 ? round(($completedUsers / $totalAssigned) * 100, 2) : 0;

                // Agency-wise breakdown: trainings count and completion rate per agency
                if ($hasPivot) {
                    // derive agencies via participants -> users -> agencies table
                    if ($agencyTable) {
                        $agencyBreakdown = DB::table("{$pivot} as p")
                            ->join('users as u', 'p.trainee_id', '=', 'u.id')
                            ->join("{$agencyTable} as a", 'u.agency_id', '=', 'a.id')
                            ->whereIn('p.training_id', $trainingIds)
                            ->groupBy('a.id', 'a.name')
                            ->selectRaw('a.id, a.name, COUNT(DISTINCT p.training_id) as trainings_count, COUNT(DISTINCT p.trainee_id) as total_assigned, SUM(CASE WHEN p.status = "completed" THEN 1 ELSE 0 END) as total_completed')
                            ->get()
                            ->map(function ($row) {
                                $row->completion_rate = $row->total_assigned > 0 ? round(($row->total_completed / $row->total_assigned) * 100, 2) : 0;
                                return $row;
                            });
                    } else {
                        $agencyBreakdown = collect();
                    }
                } elseif ($hasAgencyCol && $agencyTable) {
                    // trainings table has agency_id
                    $agencyBreakdown = DB::table('trainings as t')
                        ->join("{$agencyTable} as a", 't.agency_id', '=', 'a.id')
                        ->whereIn('t.id', $trainingIds)
                        ->groupBy('a.id', 'a.name')
                        ->selectRaw('a.id, a.name, COUNT(DISTINCT t.id) as trainings_count')
                        ->get()
                        ->map(function ($row) {
                            $row->total_assigned = 0;
                            $row->total_completed = 0;
                            $row->completion_rate = 0;
                            return $row;
                        });
                } else {
                    $agencyBreakdown = collect();
                }

                // Region heatmap: number of trainings per region
                if ($hasRegion) {
                    $regionHeat = DB::table('trainings as t')
                        ->join('regions as r', 't.region_id', '=', 'r.id')
                        ->whereIn('t.id', $trainingIds)
                        ->groupBy('r.id', 'r.name')
                        ->selectRaw('r.id, r.name, COUNT(DISTINCT t.id) as trainings_count')
                        ->get();
                } elseif ($hasPivot) {
                    // derive regions from participants -> users -> agencies -> regions
                    if ($agencyTable && Schema::hasTable('regions')) {
                        $regionNameCol = Schema::hasColumn('regions', 'region') ? 'region' : 'name';
                        $regionHeat = DB::table("{$pivot} as p")
                            ->join('users as u', 'p.trainee_id', '=', 'u.id')
                            ->join("{$agencyTable} as a", 'u.agency_id', '=', 'a.id')
                            ->join('regions as r', 'a.region_id', '=', 'r.id')
                            ->whereIn('p.training_id', $trainingIds)
                            ->groupBy('r.id', "r.{$regionNameCol}")
                            ->selectRaw("r.id, r.{$regionNameCol} as name, COUNT(DISTINCT p.training_id) as trainings_count")
                            ->get();
                    } else {
                        $regionHeat = collect();
                    }
                } else {
                    $regionHeat = collect();
                }

                // State coverage
                if ($hasState) {
                    $stateCoverage = DB::table('trainings as t')
                        ->join('states as s', 't.state_id', '=', 's.id')
                        ->whereIn('t.id', $trainingIds)
                        ->groupBy('s.id', 's.name')
                        ->selectRaw('s.id, s.name, COUNT(DISTINCT t.id) as trainings_count')
                        ->get();
                } elseif ($hasPivot) {
                    // derive states from participants -> users -> agencies -> states
                    if ($agencyTable && Schema::hasTable('states')) {
                        $stateNameCol = Schema::hasColumn('states', 'name') ? 'name' : 'state';
                        $stateCoverage = DB::table("{$pivot} as p")
                            ->join('users as u', 'p.trainee_id', '=', 'u.id')
                            ->join("{$agencyTable} as a", 'u.agency_id', '=', 'a.id')
                            ->join('states as s', 'a.state_id', '=', 's.id')
                            ->whereIn('p.training_id', $trainingIds)
                            ->groupBy('s.id', "s.{$stateNameCol}")
                            ->selectRaw("s.id, s.{$stateNameCol} as name, COUNT(DISTINCT p.training_id) as trainings_count")
                            ->get();
                    } else {
                        $stateCoverage = collect();
                    }
                } else {
                    $stateCoverage = collect();
                }
                // Monthly training trend (by start date column if available)
                if ($startCol) {
                    $monthlyTrend = DB::table('trainings')
                        ->whereIn('id', $trainingIds)
                        ->selectRaw("DATE_FORMAT({$startCol}, '%Y-%m') as month, COUNT(*) as total")
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get();
                } elseif ($hasPivot) {
                    // approximate monthly trend based on participant created_at or updated_at if trainings has no start_date
                    $dateCol = Schema::hasColumn($pivot, 'created_at') ? 'created_at' : (Schema::hasColumn($pivot, 'updated_at') ? 'updated_at' : null);
                    if ($dateCol) {
                        $monthlyTrend = DB::table("{$pivot}")
                            ->whereIn('training_id', $trainingIds)
                            ->selectRaw("DATE_FORMAT({$dateCol}, '%Y-%m') as month, COUNT(DISTINCT training_id) as total")
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();
                    } else {
                        $monthlyTrend = collect();
                    }
                } else {
                    $monthlyTrend = collect();
                }

                // Top 5 trainings by completion
                if ($hasPivot) {
                    $topTrainings = DB::table('training_participants as tt')
                        ->join('trainings as t', 'tt.training_id', '=', 't.id')
                        ->whereIn('tt.training_id', $trainingIds)
                        ->groupBy('t.id', 't.title')
                        ->selectRaw('t.id, t.title as name, COUNT(tt.trainee_id) as total_assigned, SUM(CASE WHEN tt.status = "completed" THEN 1 ELSE 0 END) as completed')
                        ->get()
                        ->map(function ($row) {
                            $row->completion_rate = $row->total_assigned > 0 ? round(($row->completed / $row->total_assigned) * 100, 2) : 0;
                            return $row;
                        })
                        ->sortByDesc('completion_rate')
                        ->take(5)
                        ->values();
                } else {
                    $topTrainings = collect();
                }

                // Bottom 5 trainings by completion (lagging)
                if ($hasPivot) {
                    $lagging = DB::table('training_participants as tt')
                        ->join('trainings as t', 'tt.training_id', '=', 't.id')
                        ->whereIn('tt.training_id', $trainingIds)
                        ->groupBy('t.id', 't.title')
                        ->selectRaw('t.id, t.title as name, COUNT(tt.trainee_id) as total_assigned, SUM(CASE WHEN tt.status = "completed" THEN 1 ELSE 0 END) as completed')
                        ->get()
                        ->map(function ($row) {
                            $row->completion_rate = $row->total_assigned > 0 ? round(($row->completed / $row->total_assigned) * 100, 2) : 0;
                            return $row;
                        })
                        ->sortBy('completion_rate')
                        ->take(5)
                        ->values();
                } else {
                    $lagging = collect();
                }

                return [
                    'total_trainings' => $totalTrainings,
                    'upcoming' => $upcoming,
                    'ongoing' => $ongoing,
                    'completed_trainings' => $completed,
                    'total_assigned' => $totalAssigned,
                    'completed_users' => $completedUsers,
                    'inprogress_users' => $inprogressUsers,
                    'not_started_users' => $notStartedUsers,
                    'global_completion_rate' => $globalCompletionRate,
                    'agency_breakdown' => $agencyBreakdown,
                    'region_heat' => $regionHeat,
                    'state_coverage' => $stateCoverage,
                    'monthly_trend' => $monthlyTrend,
                    'top_trainings' => $topTrainings,
                    'lagging_trainings' => $lagging,
                ];
            });

            return response()->json(["ok" => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            return response()->json(["ok" => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Training list for table with pagination and filters
     */
    public function getTrainingList(Request $request)
    {
        $filters = $this->buildFilters($request);
        // detect pivot and agency table as above
        $pivotCandidates = ['training_participants', 'training_participants', 'training_participant', 'training_partiviptant', 'training_participent', 'training_participantss'];
        $pivot = null;
        foreach ($pivotCandidates as $cand) { if (Schema::hasTable($cand)) { $pivot = $cand; break; } }
        $agencyTable = Schema::hasTable('agencies') ? 'agencies' : (Schema::hasTable('partners') ? 'partners' : null);

        $query = DB::table('trainings as t');

        $hasAgencyCol = Schema::hasColumn('trainings', 'agency_id');
        $hasRegionCol = Schema::hasColumn('trainings', 'region_id');
        $hasStateCol = Schema::hasColumn('trainings', 'state_id');

        // detect start/end date columns for trainings
        $startCol = Schema::hasColumn('trainings', 'start_date_time') ? 'start_date_time' : (Schema::hasColumn('trainings', 'start_date') ? 'start_date' : null);
        $endCol = Schema::hasColumn('trainings', 'end_date_time') ? 'end_date_time' : (Schema::hasColumn('trainings', 'end_date') ? 'end_date' : null);

        // join agency table only if trainings table points to agency
        $selectAgency = 'NULL as agency';
        if ($hasAgencyCol && $agencyTable) {
            $query->leftJoin("{$agencyTable} as p", 't.agency_id', '=', 'p.id');
            $selectAgency = 'p.name as agency';
        }

        // join pivot if exists
        if ($pivot) {
            $query->leftJoin("{$pivot} as tt", 't.id', '=', 'tt.training_id');
        }

        // filters
        $query->when($filters['agency_id'], function ($q) use ($filters, $hasAgencyCol, $pivot) {
            if ($hasAgencyCol) {
                $q->where('t.agency_id', $filters['agency_id']);
            } elseif ($pivot) {
                $q->whereExists(function ($sub) use ($filters, $pivot) {
                    $sub->select(DB::raw(1))->from("{$pivot} as p")->join('users as u', 'p.trainee_id', '=', 'u.id')->whereRaw('p.training_id = t.id')->where('u.agency_id', $filters['agency_id']);
                });
            }
        })
        ->when($filters['region_id'], function ($q) use ($filters, $hasRegionCol, $pivot) {
            if ($hasRegionCol) {
                $q->where('t.region_id', $filters['region_id']);
            } elseif ($pivot) {
                $q->whereExists(function ($sub) use ($filters, $pivot) {
                    $sub->select(DB::raw(1))->from("{$pivot} as p")->join('users as u', 'p.trainee_id', '=', 'u.id')->whereRaw('p.training_id = t.id')->where('u.region_id', $filters['region_id']);
                });
            }
        })
        ->when($filters['state_id'], function ($q) use ($filters, $hasStateCol, $pivot) {
            if ($hasStateCol) {
                $q->where('t.state_id', $filters['state_id']);
            } elseif ($pivot) {
                $q->whereExists(function ($sub) use ($filters, $pivot) {
                    $sub->select(DB::raw(1))->from("{$pivot} as p")->join('users as u', 'p.trainee_id', '=', 'u.id')->whereRaw('p.training_id = t.id')->where('u.state_id', $filters['state_id']);
                });
            }
        })
        ->when($filters['training_id'], function ($q) use ($filters) {
            $q->where('t.id', $filters['training_id']);
        })
        ->when($filters['from_date'], function ($q) use ($filters, $startCol) {
            if ($startCol) $q->whereDate("t.{$startCol}", '>=', $filters['from_date']);
        })
        ->when($filters['to_date'], function ($q) use ($filters, $endCol) {
            if ($endCol) $q->whereDate("t.{$endCol}", '<=', $filters['to_date']);
        });

        // build select and groupBy depending on pivot presence and date columns
        $groupCols = ['t.id', 't.title'];
        $startSelect = $startCol ? "t.{$startCol} as start_date" : "NULL as start_date";
        $endSelect = $endCol ? "t.{$endCol} as end_date" : "NULL as end_date";
        if ($startCol) $groupCols[] = "t.{$startCol}";
        if ($endCol) $groupCols[] = "t.{$endCol}";
        if ($selectAgency !== 'NULL as agency') $groupCols[] = 'p.name';

        $query->groupBy(...$groupCols);

        if ($pivot) {
            $query->selectRaw("t.id, t.title as name, {$selectAgency}, {$startSelect}, {$endSelect}, COUNT(DISTINCT tt.trainee_id) as total_assigned, SUM(CASE WHEN tt.status = 'completed' THEN 1 ELSE 0 END) as completed, SUM(CASE WHEN tt.status = 'in-progress' THEN 1 ELSE 0 END) as inprogress");
        } else {
            $query->selectRaw("t.id, t.title as name, {$selectAgency}, {$startSelect}, {$endSelect}, 0 as total_assigned, 0 as completed, 0 as inprogress");
        }

        if ($startCol) $query->orderBy("t.{$startCol}", 'desc'); else $query->orderBy('t.id', 'desc');

        $perPage = intval($request->get('per_page', 10));
        $page = intval($request->get('page', 1));

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        // Compute not started & completion % for each row
        $results->getCollection()->transform(function ($row) {
            $row->not_started = max(0, ($row->total_assigned - ($row->completed + $row->inprogress)));
            $row->completion_pct = $row->total_assigned > 0 ? round(($row->completed / $row->total_assigned) * 100, 2) : 0;
            $today = date('Y-m-d');
            if (!empty($row->start_date) && $row->start_date > $today) {
                $row->status = 'Upcoming';
            } elseif (!empty($row->end_date) && $row->end_date < $today) {
                $row->status = 'Completed';
            } else {
                $row->status = 'Ongoing';
            }
            return $row;
        });

        return response()->json($results);
    }

    /**
     * View training detail (summary + chart data)
     */
    public function viewTraining($training_id)
    {
        try {
            // detect agency table
            $agencyTable = Schema::hasTable('agencies') ? 'agencies' : (Schema::hasTable('partners') ? 'partners' : null);

            $tQuery = DB::table('trainings as t');
            if ($agencyTable && Schema::hasColumn('trainings', 'agency_id')) {
                $tQuery->leftJoin("{$agencyTable} as p", 't.agency_id', '=', 'p.id');
                $selectAgency = 'p.name as agency';
            } else {
                $selectAgency = 'NULL as agency';
            }
            if (Schema::hasTable('regions') && Schema::hasColumn('trainings', 'region_id')) {
                $tQuery->leftJoin('regions as r', 't.region_id', '=', 'r.id');
                $selectRegion = 'r.name as region';
            } else {
                $selectRegion = 'NULL as region';
            }
            if (Schema::hasTable('states') && Schema::hasColumn('trainings', 'state_id')) {
                $tQuery->leftJoin('states as s', 't.state_id', '=', 's.id');
                $selectState = 's.name as state';
            } else {
                $selectState = 'NULL as state';
            }

            $training = $tQuery->where('t.id', $training_id)
                ->select('t.*', DB::raw($selectAgency), DB::raw($selectRegion), DB::raw($selectState))
                ->first();

            if (!$training) {
                return response()->json(['ok' => false, 'error' => 'Training not found'], 404);
            }

            // detect pivot table
            $pivotCandidates = ['training_participants', 'training_participants', 'training_participant', 'training_partiviptant', 'training_participent', 'training_participantss'];
            $pivot = null;
            foreach ($pivotCandidates as $cand) { if (Schema::hasTable($cand)) { $pivot = $cand; break; } }

            if ($pivot) {
                $totalAssigned = DB::table($pivot)->where('training_id', $training_id)->distinct('trainee_id')->count('trainee_id');
                $completed = DB::table($pivot)->where('training_id', $training_id)->where('status', 'completed')->distinct('trainee_id')->count('trainee_id');
                $inprogress = DB::table($pivot)->where('training_id', $training_id)->where('status', 'in-progress')->distinct('trainee_id')->count('trainee_id');
            } else {
                $totalAssigned = $completed = $inprogress = 0;
            }
            $notStarted = max(0, $totalAssigned - $completed - $inprogress);
            $completionPct = $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100, 2) : 0;

            // Completion over time (daily)
            if ($pivot && Schema::hasColumn($pivot, 'updated_at')) {
                $daily = DB::table($pivot)
                    ->where('training_id', $training_id)
                    ->where('status', 'completed')
                    ->selectRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') as day, COUNT(*) as completed")
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();
            } else {
                $daily = collect();
            }

            // Region-wise distribution (by users -> agencies -> regions)
            if ($pivot && $agencyTable && Schema::hasTable('regions')) {
                $regionDist = DB::table("{$pivot} as tt")
                    ->join('users as u', 'tt.trainee_id', '=', 'u.id')
                    ->join("{$agencyTable} as a", 'u.agency_id', '=', 'a.id')
                    ->join('regions as r', 'a.region_id', '=', 'r.id')
                    ->where('tt.training_id', $training_id)
                    ->groupBy('r.id', 'r.name')
                    ->selectRaw('r.id, r.name, COUNT(DISTINCT u.id) as users_count')
                    ->get();
            } else {
                $regionDist = collect();
            }

            // Agency/department wise completion (by user->agency_id or department)
            if ($pivot) {
                $agencyTable = Schema::hasTable('agencies') ? 'agencies' : (Schema::hasTable('partners') ? 'partners' : null);
                if ($agencyTable) {
                    $agencyDist = DB::table("{$pivot} as tt")
                        ->join('users as u', 'tt.trainee_id', '=', 'u.id')
                        ->join("{$agencyTable} as p", 'u.agency_id', '=', 'p.id')
                        ->where('tt.training_id', $training_id)
                        ->groupBy('p.id', 'p.name')
                        ->selectRaw('p.id, p.name, COUNT(DISTINCT u.id) as users_count, SUM(CASE WHEN tt.status = "completed" THEN 1 ELSE 0 END) as completed')
                        ->get()
                        ->map(function ($row) {
                            $row->completion_rate = $row->users_count > 0 ? round(($row->completed / $row->users_count) * 100, 2) : 0;
                            return $row;
                        });
                } else {
                    $agencyDist = collect();
                }
            } else {
                $agencyDist = collect();
            }

            $summary = [
                'training' => $training,
                'total_assigned' => $totalAssigned,
                'completed' => $completed,
                'inprogress' => $inprogress,
                'not_started' => $notStarted,
                'completion_pct' => $completionPct,
                'daily' => $daily,
                'region_dist' => $regionDist,
                'agency_dist' => $agencyDist,
            ];

            return response()->json(['ok' => true, 'data' => $summary]);
        } catch (\Exception $e) {
            Log::error('View training error: ' . $e->getMessage());
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get users assigned to a training (paginated)
     */
    public function getTrainingUsers(Request $request, $training_id)
    {
        $perPage = intval($request->get('per_page', 15));
        $page = intval($request->get('page', 1));
        // detect pivot
        $pivotCandidates = ['training_participants', 'training_participants', 'training_participant', 'training_partiviptant', 'training_participent', 'training_participantss'];
        $pivot = null;
        foreach ($pivotCandidates as $cand) { if (Schema::hasTable($cand)) { $pivot = $cand; break; } }

        if (! $pivot) {
            return response()->json([ 'data' => [], 'current_page' => 1, 'per_page' => $perPage, 'total' => 0 ]);
        }

        $query = DB::table("{$pivot} as tt")
            ->join('users as u', 'tt.trainee_id', '=', 'u.id')
            ->where('tt.training_id', $training_id)
            ->selectRaw('u.id, u.first_name, u.last_name, u.email, u.mobile, tt.status, tt.updated_at as completion_date, tt.certificate_path')
            ->orderBy('u.first_name');

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($results);
    }

    /**
     * Build filter array from request
     */
    private function buildFilters(Request $request)
    {
        return [
            'agency_id' => $request->get('agency_id') ?: null,
            'region_id' => $request->get('region_id') ?: null,
            'state_id' => $request->get('state_id') ?: null,
            'training_id' => $request->get('training_id') ?: null,
            'from_date' => $request->get('from_date') ?: null,
            'to_date' => $request->get('to_date') ?: null,
        ];
    }

    private function emptyStats()
    {
        return [
            'total_trainings' => 0,
            'upcoming' => 0,
            'ongoing' => 0,
            'completed_trainings' => 0,
            'total_assigned' => 0,
            'completed_users' => 0,
            'inprogress_users' => 0,
            'not_started_users' => 0,
            'global_completion_rate' => 0,
            'agency_breakdown' => [],
            'region_heat' => [],
            'state_coverage' => [],
            'monthly_trend' => [],
            'top_trainings' => [],
            'lagging_trainings' => [],
        ];
    }
}
