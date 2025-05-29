<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css')}}">

</head>
	
	<body class="login"> 
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
					
		@yield('content')
		<script type="text/javascript">
			function show_message(message,message_type) {
					$().toastmessage('showToast', {	
						text: message,
						sticky: false,
						position: 'top-right',
						type: message_type,
					});
				}
		</script>
		
{{-- For Toaster Notification --}}
		<link href="{{ asset('css/notification/jquery.toastmessage.css') }}" rel="stylesheet"/>
		<script src="{{ URL::asset('css/notification/jquery.toastmessage.js') }}" ></script>
	</body>

	
</html>
<style>
.error-message{
	color:red;
}
.help-inline{
	color:red;
}
</style>

