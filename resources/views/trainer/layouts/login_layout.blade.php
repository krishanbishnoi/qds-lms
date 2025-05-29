<!DOCTYPE html>
<html>

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>LMS</title>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<!-- plugins:css -->
		<link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
		<link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
		<link href="{{ asset('css/developer.css') }}"   rel="stylesheet">
		<!-- endinject -->
		<!-- Plugin css for this page -->
		<!-- End plugin css for this page -->
		<!-- inject:css -->
		<!-- endinject -->
		<!-- Layout styles -->
		<link rel="stylesheet" href="{{ asset('css/style.css') }}">
		<!-- End layout styles -->
		<link rel="shortcut icon" href="{{ asset('lms-img/qdegrees-fav-icon.png') }}" />
		<link href="{{ asset('css/notification/jquery.toastmessage.css') }}" rel="stylesheet"/>
		<script src="{{ URL::asset('css/notification/jquery.toastmessage.js') }}" ></script>
	</head>

	<body class="hold-transition login-page  pace-done">
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
		    <!-- container-scroller -->
    <!-- plugins:js -->
		<script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
		<!-- endinject -->
		<!-- Plugin js for this page -->
		<!-- End plugin js for this page -->
		<!-- inject:js -->
		<script src="{{ asset('js/off-canvas.js') }}"></script>
		<script src="{{ asset('js/hoverable-collapse.js') }}"></script>
		<script src="{{ asset('js/misc.js') }}"></script>
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

