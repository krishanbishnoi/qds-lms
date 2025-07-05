@extends('front.layouts.trainee-default')
@section('content')

    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{--  Sidebar  --}}
                @include('front.layouts.trainee-sidebar')

                <div class="dashboard_content">
                    <div class="dashboard_head mb-md-5 d-lg-flex justify-content-between">
                        <div class="">
                            <h1 class="fs-2">My <span>{{ $sectionNameSingular }}.</span></h1>
                            <p>Listing</p>
                        </div>
                        {{-- Notification --}}
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
                                                    <th>Test Name</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>End Date & Time</th>
                                                    <th>Passing Score</th>
                                                    {{-- <th class="text-center">Details</th> --}}
                                                    <th class="text-center">Continue Test</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($ongoing))
                                                    @foreach ($ongoing as $test)
                                                        @php
                                                            $testResult = App\Models\TestResult::where(
                                                                'test_id',
                                                                $test->id,
                                                            )
                                                                ->where('user_id', Auth::user()->id)
                                                                ->orderBy('id', 'desc')
                                                                ->first();
                                                            $testParticipant = App\Models\TestParticipants::where(
                                                                'test_id',
                                                                $test->id,
                                                            )
                                                                ->where('trainee_id', Auth::user()->id)
                                                                ->first();
                                                        @endphp
                                                        <tr>
                                                            <td>{{ ucwords($test->title) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($test->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($test->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($test->end_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($test->end_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ $test->minimum_marks }}</td>
                                                            <td class="text-center">
                                                                <div class="actionGroup">
                                                                    @if (
                                                                        !$testResult || // If $testResult is null
                                                                            ($testResult->result == 'Failed' &&
                                                                                $testParticipant &&
                                                                                ($testParticipant->status == 0 ||
                                                                                    ($testParticipant->status == 1 &&
                                                                                        $testParticipant->number_of_attempts > $testParticipant->user_attempts))))
                                                                        <a href="{{ route('userTestDetails.index', $test->id) }}"
                                                                            class="continueBtn"><img
                                                                                src="{{ asset('front/img/continue-icon.svg') }}"
                                                                                alt="img" width="47"height="28">
                                                                        </a>
                                                                    @else
                                                                        <a href="javascript:void()"data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"
                                                                            title="This Test Already Submitted."
                                                                            class="continueBtn"><img
                                                                                src="{{ asset('front/img/continue-icon.svg') }}"
                                                                                alt="img" width="47"
                                                                                height="28"></a>
                                                                    @endif
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
                                                    <th>Test Name</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>End Date & Time</th>
                                                    <th>Passing Score</th>
                                                    {{-- <th class="text-center">Details</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($upcoming))
                                                    @foreach ($upcoming as $test)
                                                        <tr>
                                                            <td>{{ ucwords($test->title) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($test->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($test->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($test->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($test->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ $test->minimum_marks }}</td>
                                                            <td class="text-center">
                                                                @if (!empty($test->id))
                                                                    <div class="actionGroup">
                                                                        <a href="javascript:void(0);" class="openModal"
                                                                            data-bs-toggle="modal"
                                                                            data-id="{{ $test->id }}"
                                                                            data-title="{{ $test->title }}"data-description="{!! $test->description !!}">

                                                                            <img src="{{ asset('front/img/det-icon.svg') }}"
                                                                                alt="img" width="28"
                                                                                height="28"></a>
                                                                    </div>
                                                                @endif
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
                                                    <th>Test Name</th>
                                                    <th>Starting Date & Time</th>
                                                    <th>Passing Score</th>
                                                    <th>Obtained Score</th>
                                                    <th>Result</th>
                                                    <th>Details</th>
                                                    {{-- <th>Report</th>
                                                    <th>Reattempt</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($completedTests))
                                                    @foreach ($completedTests as $test)
                                                        <tr>
                                                            <td>{{ ucwords($test->title) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($test->start_date_time)->format('Y-m-d') }}
                                                                |
                                                                {{ \Carbon\Carbon::parse($test->start_date_time)->format('h:i') }}
                                                            </td>
                                                            <td>{{ $test->minimum_marks }}%</td>
                                                            <td>{{ $test->score ?? '0' }}%</td>
                                                            @if ($test->score >= $test->minimum_marks ?? '0')
                                                                <td class="greentxt">Passed</td>
                                                            @else
                                                                <td class="danger-text">Failed</td>
                                                            @endif
                                                            <td class="text-center">

                                                                <div class="actionGroup"> <a href="javascript:void(0);"
                                                                        class="btn-show-details"
                                                                        data-test-id="{{ $test->id }}">
                                                                        <img src="{{ asset('front/img/detail-icon.svg') }}"
                                                                            alt="img" width="28" height="28">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            {{-- <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        class=""><img
                                                                            src="{{ asset('front/img/view-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a></div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="actionGroup"><a href="javascript:void(0);"
                                                                        class=""><img
                                                                            src="{{ asset('front/img/reattempt-icon.svg') }}"
                                                                            alt="img" width="28"
                                                                            height="28"></a></div>
                                                            </td> --}}
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


    <!--Test Detail Modal -->
    <div class="modal fade trainingDetailModal" id="trainingDetailModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Test Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong class="mb-2">Test Name</strong>
                    <p></p>
                    <strong class="mt-3  mb-2">About this Test</strong>
                    <p></p>
                    <strong class="mt-3 mb-2">Other Details</strong>
                    <p>Test Type : </p>
                    <p>Minimum Marks : 50</p>
                    <p>Obtained Score : 50</p>
                    <p>Results : </p>
                    <strong class="mt-3 mb-2">Certificates</strong>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                    <p>Complete the training and test to achieve this certificate</p>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-show-details').on('click', function() {
                const testId = $(this).data('test-id');
                const completedTests = {!! json_encode($completedTests) !!};
                const test = completedTests.find(test => test.id === testId);

                // Check if the test is not null before proceeding
                if (test) {
                    // Populate the modal with the test details
                    $('.modal-title').text('Test Details - ' + test.title);
                    $('.modal-body').html(`
                    <strong class="mb-2">Test Name</strong>
                    <p>${test.title}</p>
                    <strong class="mt-3  mb-2">About this Test</strong>
                    <p>${test.description}</p>
                    <strong class="mt-3 mb-2">Other Details</strong>
                    <p>Test Type : ${test.type}</p>
                    <p>Minimum Marks : ${test.minimum_marks}%</p>
                    <p>Obtained Score : ${test.score}%</p>
                    ${test.score >= test.minimum_marks ? '<td class="greentxt">Result : Passed</td>' : '<td class="danger-text">Result : Failed</td>'}
                `);

                    // Show the modal
                    $('#trainingDetailModal').modal('show');
                } else {
                    // Handle the case when the test is null or not found
                    // You can show an error message or handle it as per your requirement
                    alert('Test details not available.');
                }
            });
        });
    </script>

    <script>
        /* For open Email detail popup */
        function getPopupClient(id) {
            $.ajax({
                url: '<?php echo URL::to('/training-logs/training_details'); ?>/' + id,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },

                success: function(r) {
                    $("#trainingDetailModals").html(r);
                    $("#trainingDetailModals").modal('show');
                }
            });
        }
    </script>


    {{-- <script>
        // $(document).on('click', '.openModal', function() {
        jQuery(document).on('click', '.openModal', function() {

            var id = $(this).data('id');
            var title = $(this).data('title');
            var description = $(this).data('description');

            $('#trainingTitle').text(title);
            $('#trainingDescription').text(description);
            ta('id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            // Remove HTML tags from the description
            var descriptionText = $('<div>').html(description).text();


            $('#trainingTitle').text(title);
            $('#trainingDescription').text(descriptionText);

            $('#trainingDetailModals').modal('show');
        })
    </script> --}}
@stop
