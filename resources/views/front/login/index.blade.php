<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css')}}">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>

<body class="login">
    @if(Session::has('error'))
    <script type="text/javascript">
        $(document).ready(function(e) {
            show_message("{{{ Session::get('error') }}}", 'error');
        });
    </script>
    @endif

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
    <div class="form-panel">
        <div class="text-center ">
            <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="QDegrees-logo" width="130" height="33">
        </div>
        <div class="from-content">
            <h2 class="fs-5 fw-bold mt-4 mb-3">Log In to your account</h2>
            <p class="mb-3 mb-md-4">Customer data privacy involves protecting and handling sensitive personal
                information that an individual provides during transactions.
            </p>
            {{ Form::open(['role' => 'form', 'method' => 'post', 'url' => '/login', 'enctype' => 'multipart/form-data']) }}
            <div class="form-group mb-3 position-relative">
                <input type="text" name="email" class="form-control pe-5" placeholder="Enter Email">
                <span class="inputIcon">@</span>
                <div class="error-message help-inline">
                    <?php echo $errors->first('email'); ?>
                </div>
            </div>
            <div class="form-group position-relative mb-2">
                <input id="password" name="password" type="password" class="form-control  pe-5" placeholder="Enter Password">
                <button type="button" onclick="showPassword()" class="inputIcon viewPassword"><i class="bi bi-eye-slash text-secondary"></i></button>
                <div class="error-message help-inline">
                    <?php echo $errors->first('password'); ?>
                </div>
            </div>
            <div class="form-group frgot_pass text-end mb-3">
                <a href="#forget_password" data-bs-toggle="modal" class="my-2"><u> Forget Password</u></a>
            </div>
            <div class="form-group">
                <button name="login" type="submit" class="btn w-100 btn-secondary">Log In</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <!-- modal frogot password modal start -->
    <div class="modal fade forget_password" id="forget_password">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="text-center ">
                        <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="QDegrees-logo" width="130" height="33">
                    </div>
                    <h2 class="fs-5 fw-bold my-4">Link to reset password is sent to your email</h2>
                    <p class="mb-3">Customer data privacy involves protecting and handling sensitive personal
                        information that an individual provides during </p>
                        {{ Form::open(['role' => 'form', 'url' => '/send_password', 'class' => 'forms-sample']) }}
                    <div class="form-group mb-4 position-relative">
                        <input type="email" name="email" class="form-control pe-5" placeholder="Enter Email">
                        <span class="inputIcon">@</span>
                    </div>
                    <div class="error-message help-inline">
                        <?php echo $errors->first('email'); ?>
                    </div>
                    <div class="form-group">
                        <button class="btn w-100 btn-secondary">Submit</button>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
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

        function showPassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
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
