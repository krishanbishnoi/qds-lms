<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | QDegrees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css')}}">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .backgroundCard{
            width: 500px;
            background-color: #ECECEC !important;
            padding: 25px;
        }
    </style>

</head>

<body class="login" style="background-image: none">
    {{-- @if(Session::has('error'))
    <script type="text/javascript">
        $(document).ready(function(e) {
            show_message("{{{ Session::get('error') }}}", 'error');
        });
    </script>
    @endif --}}

    @if(Session::has('success'))
    <script type="text/javascript">
        $(document).ready(function(e) {
            show_message("{{{ Session::get('success') }}}", 'success');
        });
    </script>
    @endif

    @if(Session::has('flash_notice'))
    <script type="text/javascript">
        $(document).ready(function(e) {
            show_message("{{{ Session::get('flash_notice') }}}", 'success');
        });
    </script>
    @endif

     <header class="traineeHead">
            <div class="container-fluid">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="logoSec">
                        <a href="{{ route('front.dashboard') }}"><img src="../lms-img/qdegrees-logo.svg" alt="logo"
                                width="130" height="33px"></a>
                    </div>
                    <div class="courseProgress">
                        <a href="{{ route('front.dashboard') }}" class="exitBtn">
                            <img src="../front/img/exit.svg" alt="icon" width="23" height="23">
                        </a>
                    </div>
                </div>
            </div>
        </header>
    <div class=" rounded-2 bg-transparent backgroundCard">
        <div class="text-center ">
            <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="QDegrees-logo" width="130" height="33">
        </div>
        <div class="from-content text-center">
            <h2 class="fs-5 fw-bold mt-4 mb-3">You have @if ($testAttendStatus == 1) successfully @elseif($testAttendStatus == 2) already
                
            @endif submitted this test.</h2>
            <p class="mb-3 mb-md-4">
                It seems like you've already completed and submitted the Test. If you have any additional questions or need further assistance, please contact to Admin.
            </p>
            @if(isset($trainingId))
            <a href="{{ route('userTrainingDetails.index', ['id' => $trainingId]) }}"
                                            class="btn btn-secondary smallBtn py-1 px-4">Next Course</a>
                                            @endif
        </div>
    </div>

    <script type="text/javascript">
        function show_message(message, message_type) {
            $().toastmessage('showToast', {
                text: message,
                sticky: false,
                position: 'top-right',
                type: message_type,
            });
        }
    </script>

    {{-- For Toaster Notification --}}
    <link href="{{ asset('css/notification/jquery.toastmessage.css') }}" rel="stylesheet" />
    <script src="{{ URL::asset('css/notification/jquery.toastmessage.js') }}"></script>
</body>


</html>
<style>
    .error-message {
        color: red;
    }

    .help-inline {
        color: red;
    }
</style>
