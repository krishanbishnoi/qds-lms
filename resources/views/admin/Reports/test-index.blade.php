@extends('admin.layouts.default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="{{ asset('all-cdn/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>

                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns d-flex justify-content-between mb-3">
                                <h1 class="box-title">Test List</h1>
                                <form method="GET" action="{{ route('Reports.test') }}" class="d-flex">
                                    <!-- Test Type Filter -->
                                    <select name="test_type" class="form-control me-2">
                                        <option value="">All Types</option>
                                        <option value="regular_test" {{ $testType == 'regular_test' ? 'selected' : '' }}>
                                            Regular Test
                                        </option>
                                        <option value="training_test" {{ $testType == 'training_test' ? 'selected' : '' }}>
                                            Training Test
                                        </option>
                                    </select>

                                    <!-- Search Test Name -->
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Test Name" value="{{ request('search') }}">

                                    <!-- Filter Button -->
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                            </div>
                        </div>

                        <div class="trainingTabContent">
                            <div class="tab-content" id="tab-Content">
                                <div class="tab-pane fade show active" id="allTab" role="tabpanel">
                                    <div class="box p-0">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Test Name</th>
                                                        <th>Type</th>
                                                        <th class="text-center">Passing Score</th>
                                                        <th class="text-center">Total Users</th>
                                                        <th class="text-center">Passed Users</th>
                                                        <th class="text-center">Failed Users</th>
                                                        <th class="text-center">Report</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($allTest as $test)
                                                        <tr>
                                                            <td class="text-wrap">
                                                                {{-- Show test title for both types --}}
                                                                {{ isset($test->test) ? $test->test->title : $test->title ?? 'N/A' }}
                                                            </td>

                                                            <td>
                                                                {{-- Convert test type to readable format --}}
                                                                {{ isset($test->test) ? ucwords(str_replace('_', ' ', $test->test->type)) : ucwords(str_replace('_', ' ', $test->type ?? '')) }}
                                                            </td>

                                                            <td class="text-center">
                                                                {{-- Show minimum marks for both test types --}}
                                                                {{ isset($test->test) ? $test->test->minimum_marks ?? 'N/A' : $test->minimum_marks ?? 'N/A' }}%
                                                            </td>

                                                            <td class="text-center">
                                                                {{-- Handle participant count separately for both test types --}}
                                                                @if (isset($test) && $testType == 'training_test')
                                                                    {{ is_countable($test->training_test_participants) ? count($test->training_test_participants) : 0 }}
                                                                @else
                                                                    {{ is_countable($test->test_participants) ? count($test->test_participants) : 0 }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                {{-- Passed Users Count --}}
                                                                @if ($testType == 'training_test')
                                                                    {{ collect($test->training_test_results)->groupBy('user_id')->filter(fn($attempts) => $attempts->sortByDesc('attempt_number')->first()->result == 'Passed')->count() }}
                                                                @else
                                                                    {{ collect($test->test_results)->groupBy('user_id')->filter(fn($attempts) => $attempts->sortByDesc('attempt_number')->first()->result == 'Passed')->count() }}
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                {{-- Failed Users Count --}}
                                                                @if ($testType == 'training_test')
                                                                    {{ collect($test->training_test_results)->groupBy('user_id')->filter(fn($attempts) => $attempts->sortByDesc('attempt_number')->first()->result == 'Failed')->count() }}
                                                                @else
                                                                    {{ collect($test->test_results)->groupBy('user_id')->filter(fn($attempts) => $attempts->sortByDesc('attempt_number')->first()->result == 'Failed')->count() }}
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="actionGroup"><a
                                                                        href="{{ route('Reports.downloads', $test->id) }}"
                                                                        class="deletBtn"><span
                                                                            class="fas fa-download"></span></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">No tests found.</td>
                                                        </tr>
                                                    @endforelse

                                                </tbody>
                                            </table>
                                            {{-- @if ($allTest->isEmpty())
                                                <p class="text-center">Test Not Created</p>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

@stop
