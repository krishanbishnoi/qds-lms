@extends('front.layouts.trainee-default')
@section('content')
    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{-- // Sidebar  --}}
                @include('front.layouts.trainee-sidebar')
                <div class="dashboard_content">
                    <div class="dashboard_head mb-md-5 d-lg-flex justify-content-between">
                        <div class="">
                            <h1 class="fs-2">My <span>{{ $sectionNameSingular }}.</span></h1>
                            <p>Listing</p>
                        </div>
                        {{-- for ntifications file --}}
                        @include('front.layouts.notification')
                    </div>
                    <div class="trainingTab nav-tabs" id="tab" role="tablist">
                        <button class="tabButton active" data-bs-toggle="tab" data-bs-target="#ongoingTab" type="button"
                            aria-controls="ongoingTab" aria-selected="true">Ongoing</button>
                        <button class="tabButton" data-bs-toggle="tab" data-bs-target="#upcomingTab" type="button"
                            aria-controls="upcomingTab" aria-selected="false">Upcoming</button>
                        <button class="tabButton" data-bs-toggle="tab" data-bs-target="#completedTab" type="button"
                            aria-controls="completedTab" aria-selected="false">Completed</button>
                        </li>
                        </ul>
                    </div>
                    <div class="trainingTabContent">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="ongoingTab" role="tabpanel">
                                <div class="box p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Training Name</th>
                                                    <th>Training Description</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>End Date & Time</th>
                                                    <th class="text-center">Details</th>
                                                    <th class="text-center">Continue Training</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($ongoing) || is_object($ongoing))
                                                    @foreach ($ongoing as $training)
                                                        <tr>
                                                            <td>{{ ucwords($training->title) }}</td>
                                                            <td>{!! ucwords(substr($training->description, 0, 40)) !!}..</td>
                                                            <td>{{ \Carbon\Carbon::parse($training->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($training->end_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->end_date_time)->format('h:i') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        data-bs-toggle="modal"
                                                                        onclick="getPopupClient({{ $training->id }})">
                                                                        <img src="{{ asset('front/img/detail-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a
                                                                        href="{{ route('userTrainingDetails.index', $training->id) }}"
                                                                        class="continueBtn"><img
                                                                            src="{{ asset('front/img/continue-icon.svg') }}"
                                                                            alt="img" width="47"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="upcomingTab" role="tabpanel">
                                <div class="box p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Training Name</th>
                                                    <th>Training Description</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>End Date & Time</th>
                                                    <th class="text-center">Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($upcoming) || is_object($upcoming))
                                                    @foreach ($upcoming as $training)
                                                        <tr>
                                                            <td>{{ ucwords($training->title) }}</td>
                                                            <td>{!! ucwords(substr($training->description, 0, 40)) !!}</td>
                                                            <td>{{ \Carbon\Carbon::parse($training->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($training->end_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->end_date_time)->format('h:i') }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup">
                                                                    <a href="javascript:void(0);" class="openModal"
                                                                        data-bs-toggle="modal"
                                                                        onclick="getPopupClient({{ $training->id }})">
                                                                        <img src="{{ asset('front/img/detail-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="completedTab" role="tabpanel">
                                <div class="box p-0">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Training Name</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>End Date & Time</th>
                                                    <th>Passing Score</th>
                                                    <th>Obtained Score</th>
                                                    <th>Details</th>
                                                    <th>Report</th>
                                                    <th>Reattempt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (is_array($completed) || is_object($completed))
                                                    @foreach ($completed as $training)
                                                        <tr>
                                                            <td>{{ ucwords($training->title) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($training->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($training->end_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($training->end_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ $training->minimum_marks }}</td>
                                                            <td>{{ $training->minimum_marks }}</td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        class="" data-bs-toggle="modal"
                                                                        onclick="getPopupClient({{ $training->id }})"><img
                                                                            src="{{ asset('front/img/detail-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        class=""><img
                                                                            src="{{ asset('front/img/view-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        class=""><img
                                                                            src="{{ asset('front/img/reattempt-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Taraining Detail Modal -->
    <div ria-hidden="false" class="modal fade trainingDetailModal" class="modal fade in" id="trainingDetailModals"
        style="display: none;">
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        /* For open Email detail popup */
        function getPopupClient(id) {

            $.ajax({
                url: '{{ URL::to('/training-logs/training_details') }}/' + id,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(r) {
                    console.log('Button clicked with id: ' + id); // Add this line to debug

                    $("#trainingDetailModals").html(r);
                    $("#trainingDetailModals").modal('show');
                }
            });
        }
    </script>

@stop
