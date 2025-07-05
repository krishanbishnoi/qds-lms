@extends('admin.layouts.default')
@section('content')
    <style>
        .clickType {
            border-radius: 4px;
            background: rgba(53, 76, 158, .4);
            display: inline-block;
            color: var(--white);
            padding: 7px 15px;
            margin: 5px 0 15px;
            color: white;
        }
    </style>
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                Test Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Test Detail</li>
                </ol>
            </nav>
        </div>
        @include('admin.Test.partials-test-report-details', [
            'userData' => $userData,
            'test' => $test,
            'testData' => $testData,
            'testQuestions' => $testQuestions,
            'userAnswers' => $userAnswers,
            'latestAttempt' => $latestAttempt,
            'testResults' => $testResults,
        ])
    </div>
    </div>

@stop
