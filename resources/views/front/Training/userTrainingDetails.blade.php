@extends('front.layouts.trainee-default')
@section('content')
    @php
        // $startTime = strtotime($trainingDetails->start_date_time);
        $endTime = strtotime($trainingDetails->end_date_time); // get taining end dateTime
        $currentDateTime = date('Y-m-d H:i:s'); //get current end dateTime
        $currentTimestamp = strtotime($currentDateTime);
        $difference = $endTime - $currentTimestamp;
        $hours = floor($difference / (60 * 60));
        $minutes = floor(($difference - $hours * 60 * 60) / 60);
        $trainingId = request()->route('id');
    @endphp
    <header class="traineeHead">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center">
                <div class="logoSec">
                    <a href="{{ route('front.dashboard') }}"><img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="logo"
                            width="130" height="33"></a>
                </div>
                <div class="courseName">
                    <p class="mb-0">{{ $trainingDetails->title }}</p>
                </div>
                <div class="courseProgress">
                    <div class="progress-main">
                        @php
                            $progressPercentage =
                                $totalCoursesCount > 0 ? ($completedCoursesCount / $totalCoursesCount) * 100 : 0;
                            $roundedProgressPercentage = round($progressPercentage, 2);
                        @endphp
                        <div class="progress-circle" data-progress="{{ $roundedProgressPercentage }}"><img
                                src="{{ asset('front/img/processing.svg') }}" width="20" height="20"></div>
                    </div>
                    <div class="progressText">
                        <strong>Progress</strong>
                        <span>{{ $completedCoursesCount }}<i>/{{ $totalCoursesCount }}</i></span>
                    </div>
                    <button type="button" class="optionBtn d-lg-none"><img src="{{ asset('front/img/option.svg') }}"
                            alt="icon" width="23" height="23"></button>
                    <a href="{{ route('front.dashboard') }}"><button type="button" class="exitBtn"><img
                                src="{{ asset('front/img/exit.svg') }}" alt="icon" width="23"
                                height="23"></button></a>
                </div>
            </div>
        </div>
    </header>
    <div>
        @include('front.Training.userTrainingDetails-desktop')
    </div>
    <div>
        @include('front.Training.userTrainingDetails-mobile')
    </div>
    <style>
        .desktopScren {
            display: block;
        }

        .mobileScren {
            display: none;
        }

        @media screen and (max-width: 768px) {
            .desktopScren {
                display: none;
            }

            .mobileScren {
                display: block;
            }
        }
    </style>
@stop
