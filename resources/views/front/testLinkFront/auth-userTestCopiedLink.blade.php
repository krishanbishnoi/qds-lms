<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Authenticate User</title>
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
    <div class=" rounded-2 bg-transparent backgroundCard">
        <div class="text-center ">
            <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="QDegrees-logo" width="130" height="33">
        </div>
        <div class="from-content text-center">
            <h2 class="fs-5 fw-bold mt-4 mb-3">Welcome to Learning Management System Test Platform   </h2>
            <p class="mb-3 mb-md-4">
                To access the test, please enter your registered email address below. If you have a valid test invitation, you will receive further instructions on how to proceed.
            </p>
            <form action="{{ route('authenticate.testLink.attendee') }}" method="post">
                @csrf
                <div class="form-group mb-3 position-relative">
                    <input type="email" name="email" class="form-control pe-5" placeholder="Enter Email">
                    <span class="inputIcon">@</span>
                    <div class="error-message help-inline">
                        <?php echo $errors->first('email'); ?>
                    </div>
                </div>
                <input type="hidden" name="test_id" value="{{ $testDetails->id }}">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="form-group">
                    <button name="login" type="submit" class="btn w-100 btn-secondary mt-2">Authenticate</button>
                </div>
            </form>
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
