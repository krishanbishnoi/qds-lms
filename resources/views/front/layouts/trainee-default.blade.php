<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Config::get('Site.title'); ?></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="{{ asset('old/css/notification/jquery.toastmessage.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('front/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/progess-circle.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>

<div class="main-panel">

    <aside class="right-side">
             @if(Session::has('error'))
                <script type="text/javascript">
                    $(document).ready(function(e){
                        show_message("{{{ Session::get('error') }}}",'error');
                    });
                </script>
            @endif

            @if(Session::has('success'))
                <script type="text/javascript">
                    $(document).ready(function(e){
                        show_message("{{{ Session::get('success') }}}",'success');
                    });
                </script>
            @endif

            @if(Session::has('flash_notice'))
                <script type="text/javascript">
                    $(document).ready(function(e){
                        show_message("{{{ Session::get('flash_notice') }}}",'success');
                    });
                </script>
            @endif
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

    </aside>
    @yield('content')
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ URL::asset('old/css/notification/jquery.toastmessage.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front/js/custom.js') }}"></script>
