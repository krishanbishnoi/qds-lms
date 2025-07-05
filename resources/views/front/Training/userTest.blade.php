@extends('front.layouts.trainee-default')
@section('content')
    <?php
    // $startTime = strtotime($trainingDetails->start_date_time);
    $endTime = strtotime($trainingDetails->end_date_time); // get taining end dateTime
    $currentDateTime = date('Y-m-d H:i:s'); //get current end dateTime
    $currentTimestamp = strtotime($currentDateTime);
    $difference = $endTime - $currentTimestamp;
    $hours = floor($difference / (60 * 60));
    $minutes = floor(($difference - $hours * 60 * 60) / 60);
    ?>
    <body>
        <div>
            @use('Jenssegers\Agent\Agent')

            @if ((new Agent())->isMobile())
                @include('front.Training.userTest-mobile')
            @else
                @include('front.Training.userTest-desktop')
            @endif
        </div>
    @stop
