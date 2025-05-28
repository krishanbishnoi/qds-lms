<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<head>
<title>Gas Service Booking</title>
<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"  >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{{ asset('css/style.css') }}"   rel='stylesheet' type='text/css' />
<link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet"/>
<link href="{{ asset('css/notification/jquery.toastmessage.css') }}" rel="stylesheet"/>


<link href="{{ URL::asset('css/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="{{ asset('css/font.css') }}" type="text/css"/>
<link href="{{ asset('css/font-awesome.css') }}"   rel="stylesheet"> 
<link href="{{ asset('css/developer.css') }}"   rel="stylesheet"> 


<link rel="stylesheet" href="{{ asset('css/morris.css') }}"      type="text/css"/>
<!-- calendar -->
<link rel="stylesheet" href="{{ asset('css/monthly.css') }}">
<!-- //calendar -->


<!-- //font-awesome icons -->
<script src="{{ URL::asset('js/jquery2.0.3.min.js') }}" ></script>
<script src="{{ URL::asset('js/moment.js') }}" ></script>
<script src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}"></script>

<script src="{{ URL::asset('js/raphael-min.js') }}"></script>
<script src="{{ URL::asset('js/morris.js') }}" ></script>
<script src="{{ URL::asset('css/notification/jquery.toastmessage.js') }}" ></script>



</head>
<body >
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="{{ route('dashboard')}}" class="logo">
        <h2>	{{Config::get("Site.title")}} </h2>
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->

<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <!-- <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li> -->
        <!-- user login dropdown start-->
        <li class="dropdown">
		<?php $image  =    Auth::user()->image; ?>
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
			@if(USER_IMAGE_URL.$image != "")	
			
				<img height="35" width="20" src="{{ USER_IMAGE_URL.$image }}" />
			@endif
                <span class="username">{{Auth::user()->name}}</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="{{ URL('myaccount')}}"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="{{ URL('change-password')}}"><i class="fa fa-cog"></i> Change Password</a></li>
                <li><a href="{{ URL('logout')}}"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
       
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
            <?php 
				$segment1	=	Request::segment(1);
				$segment2	=	Request::segment(2); 
				$segment3	=	Request::segment(3); 
				$segment4	=	Request::segment(4); 
			?>
            


    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="{{ in_array($segment1 ,array('dashboard')) ? 'active' : '' }}" href="{{ route('dashboard')}}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
    
                <li class="sub-menu">
                    <a href="javascript:;"  class="{{ in_array($segment1 ,array('users','vendors','drivers')) ? 'active' : '' }}" >
                        <i class="fa fa-book"></i>
                        <span>User Management</span>
                    </a>
					<ul class="sub" style="{{ in_array($segment1 ,array('users','vendors','drivers')) ? 'display:block;' : 'display:none;' }}">
					<li @if($segment2=='add-new-user' || ($segment1=='users' || $segment2=='view-user' || $segment2=='edit-user')) class="active" @endif>
						<a href="{{ route('Users.index')}}"  >Customer Management</a>
					</li>
					<li @if($segment2=='add-new-driver' || ($segment1=='drivers' || $segment2=='view-driver' || $segment2=='edit-driver')) class="active" @endif>
						<a href="{{ route('Drivers.index')}}"  >Driver Management</a>
					</li>
					
					<li @if($segment2=='add-new-vendor' || ($segment1=='vendors' || $segment2=='view-vendor' || $segment2=='edit-vendor')) class="active" @endif>
						<a href="{{ route('Vendors.index')}}"  >Vendor Management</a>
					</li>

						
                    </ul>
                </li>



				

                <li class="sub-menu">
					<a href="javascript:;"  class="{{ in_array($segment1 ,array('categories','products','product-request')) ? 'active' : '' }}" >
                        <i class="fa fa-list-alt"></i>
                        <span>Category Management</span>
                    </a>
                    <ul class="sub" style="{{ in_array($segment1 ,array('categories','products')) ? 'display:block;' : 'display:none;' }}">
						<li @if($segment2=='add-new-category' ||$segment1=='products'|| ($segment1=='categories' || $segment2=='view-category' || $segment2=='edit-category') ) class="active" @endif>
							<a href="{{ route('Categories.index')}}">Category & Products</a>
						</li>
						<li @if($segment1=='product-request')) class="active" @endif>
							<a href="{{ route('ProductRequest.index')}}">Product Upload Request</a>
						</li>
                    </ul>
                </li>

				<li class="sub-menu">
                    <a href="javascript:;"  class="{{ in_array($segment1 ,array('service-fee','lowest-fee')) ? 'active' : '' }}" >
                        <i class="fa fa-list-alt"></i>
                        <span>Pricing Management</span>
                    </a>
					<ul class="sub" style="{{ in_array($segment1 ,array('service-fee','lowest-fee')) ? 'display:block;' : 'display:none;' }}">
						<li @if($segment2=='add-service-fee' || ($segment1=='service-fee' || $segment2=='view-service-fee' || $segment2=='edit-service-fee')) class="active" @endif>
							<a href="{{ route('ServiceFee.index')}}">Service Fee</a>
						</li>
						<li @if($segment2=='add-lowest-fee' || ($segment1=='lowest-fee' || $segment2=='view-lowest-fee' || $segment2=='edit-lowest-fee')) class="active" @endif>
							<a href="{{ route('LowestFee.index')}}">Lowest Fee</a>
						</li>
                    </ul>
					
                </li>
				<li class="sub-menu">
                    <a href="{{ route('Coupons.index')}}" @if($segment2=='add-new-coupon' || ($segment1=='coupons' || $segment2=='view-coupon' || $segment2=='edit-coupon')) class="active" @endif>
                        <i class="fa fa-book"></i>
                        <span>Coupon Management</span>
                    </a>
                </li>
				
				
				<li class="sub-menu">
                    <a href="{{ route('Commission.index')}}" @if($segment2=='add-new-commission' || ($segment1=='commission' || $segment2=='view-commission' || $segment2=='edit-commission')) class="active" @endif>
                        <i class="fa fa-book"></i>
                        <span>Commission Management</span>
                    </a>
                </li>
				
                <li class="sub-menu">
                    <a href="javascript:;" class="{{ in_array($segment1 ,array('email-manager','email-logs')) ? 'active' : '' }}"  >
                        <i class="fa fa-book"></i>
                        <span>Email Template</span>
                    </a>
                    <ul class="sub"  style="{{ in_array($segment1 ,array('email-manager','email-logs')) ? 'display:block;' : 'display:none;' }}">
						<li @if($segment2=='add-template' || ($segment1=='email-manager' || $segment2=='view-template' || $segment2=='edit-template')) class="active" @endif>
							<a href="{{ route('EmailTemplate.index')}}">Email Template</a>
						</li>
						<li @if(($segment1=='email-logs' )) class="active" @endif>
							<a href="{{ route('EmailLogs.listEmail')}}">Email Logs</a>
						</li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;" class="{{ in_array($segment1 ,array('settings')) ? 'active' : '' }}">
                        <i class="fa fa-book"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="sub"  style="{{ in_array($segment1 ,array('settings')) ? 'display:block;' : 'display:none;' }}">
						<li @if($segment1=='settings' && $segment3=='Site') class="active" @endif>
							<a href="{{ URL('settings/prefix/Site')}}">Site Setting</a>
						</li>
						<li  @if($segment1=='settings' && $segment3=='Reading') class="active" @endif>
							<a href="{{ URL('settings/prefix/Reading')}}">Reading Setting</a>
						</li >
                        <li  @if($segment1=='settings' && $segment3=='Social') class="active" @endif>
							<a href="{{ URL('settings/prefix/Social')}}" >Social Setting</a>
						</li>
                        <li  @if($segment1=='settings' && $segment3=='Contact') class="active" @endif>
							<a href="{{ URL('settings/prefix/Contact')}}" >Contect Setting</a>
						</li>
                    </ul>
                </li>

                
            </ul>           
         </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->

<section id="main-content">
<section class="wrapper">
</aside>
			  <!-- Main Container Start -->
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
					
					@yield('content')
				</aside>
              
 </section>

 </section>
<!-- footer -->

<!-- <div class="footer">
			<div class="wthree-copyright">
			  <p>© 2017 Visitors. All rights reserved | Design by <a href="http://w3layouts.com">W3layouts</a></p>
			</div>
            </div> -->
  <!-- / footer -->
<!--main content end-->
</section>
<script src="{{ URL::asset('js/bootstrap.js') }}"></script>
<script src="{{ URL::asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>
<script src="{{ URL::asset('js/scripts.js') }}"  ></script>
<script src="{{ URL::asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ URL::asset('js/bootbox.js') }}"></script>
<script src="{{ URL::asset('js/jquery.nicescroll.js') }}"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script  src="{{ URL::asset('js/jquery.scrollTo.js') }}"></script>

<script  src="{{ URL::asset('js/plugins/fancybox/jquery.fancybox.js') }}"></script>
<link href="{{ URL::asset('js/plugins/fancybox/jquery.fancybox.css') }}" rel="stylesheet">




<!-- morris JavaScript -->	
<script>
	$(document).ready(function() {
		//BOX BUTTON SHOW AND CLOSE
	   jQuery('.small-graph-box').hover(function() {
		  jQuery(this).find('.box-button').fadeIn('fast');
	   }, function() {
		  jQuery(this).find('.box-button').fadeOut('fast');
	   });
	   jQuery('.small-graph-box .box-close').click(function() {
		  jQuery(this).closest('.small-graph-box').fadeOut(200);
		  return false;
	   });
	   
	    //CHARTS
	    function gd(year, day, month) {
			return new Date(year, month - 1, day).getTime();
		}
		
		graphArea2 = Morris.Area({
			element: 'hero-area',
			padding: 10,
        behaveLikeLine: true,
        gridEnabled: false,
        gridLineColor: '#dddddd',
        axes: true,
        resize: true,
        smooth:true,
        pointSize: 0,
        lineWidth: 0,
        fillOpacity:0.85,
			data: [
				{period: '2015 Q1', iphone: 2668, ipad: null, itouch: 2649},
				{period: '2015 Q2', iphone: 15780, ipad: 13799, itouch: 12051},
				{period: '2015 Q3', iphone: 12920, ipad: 10975, itouch: 9910},
				{period: '2015 Q4', iphone: 8770, ipad: 6600, itouch: 6695},
				{period: '2016 Q1', iphone: 10820, ipad: 10924, itouch: 12300},
				{period: '2016 Q2', iphone: 9680, ipad: 9010, itouch: 7891},
				{period: '2016 Q3', iphone: 4830, ipad: 3805, itouch: 1598},
				{period: '2016 Q4', iphone: 15083, ipad: 8977, itouch: 5185},
				{period: '2017 Q1', iphone: 10697, ipad: 4470, itouch: 2038},
			
			],
			lineColors:['#eb6f6f','#926383','#eb6f6f'],
			xkey: 'period',
            redraw: true,
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
			pointSize: 2,
			hideHover: 'auto',
			resize: true
		});
		
	   
	});
	</script>
<!-- calendar -->
	<script type="text/javascript"  src="{{ URL::asset('js/monthly.js') }}"></script>
	<script type="text/javascript">
		$(window).load( function() {

			$('#mycalendar').monthly({
				mode: 'event',
				
			});

			$('#mycalendar2').monthly({
				mode: 'picker',
				target: '#mytarget',
				setWidth: '250px',
				startHidden: true,
				showTrigger: '#mytarget',
				stylePast: true,
				disablePast: true
			});

		switch(window.location.protocol) {
		case 'http:':
		case 'https:':
		// running on a server, should be good.
		break;
		case 'file:':
		alert('Just a heads-up, events will not work when run locally.');
		}

		});


       
	</script>

<script type="text/javascript">
	function show_message(message,message_type) {
		$().toastmessage('showToast', {	
			text: message,
			sticky: false,
			position: 'top-right',
			type: message_type,
		});
	}
			
	$(function(){
		$('.fancybox').fancybox();
		$('.fancybox-buttons').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',
		});
		
		$(document).on('click', '.delete_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to delete this ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
		
		/**
		 * Function to change status
		 *
		 * @param null
		 *
		 * @return void
		 */
		$(document).on('click', '.status_any_item', function(e){ 
			e.stopImmediatePropagation();
			url = $(this).attr('href');
			bootbox.confirm("Are you sure want to change status ?",
			function(result){
				if(result){
					window.location.replace(url);
				}
			});
			e.preventDefault();
		});
		
		$('.open').parent().addClass('active');
		$('.fancybox').fancybox();
		$('.fancybox-buttons').fancybox({
			openEffect  : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',
		});
	

		$('.sidebar > .sidebar-menu > li > a').click(function(e) {
			if(!($(this).next().hasClass("open"))) { 
				$(".treeview-menu").addClass("closed");
				$(".treeview-menu").removeClass("open");
				$(".treeview-menu.open").slideUp();
				$('.skin-black .sidebar > .sidebar-menu > li').removeClass("active");
			  
				$(this).next().slideDown();
				$(this).next().addClass("open");  
				$(this).parent().addClass("active"); 
				 
			}else {  
				e.stopPropagation(); 
				return false;  
			}
		}); 
		/**
		 * For match height of div 
		 */
		$('.items-inner').equalHeights();
		/**
		 * For tooltip
		 */
		var tooltips = $( "[title]" ).tooltip({
			position: {
				my: "right bottom+50",
				at: "right+5 top-5"
			}
		});

		$('.sidebar').slimscroll({
			height: '100%',
			size: '5px',
			color: '#ec7e07',
			alwaysVisible: false,
			distance: '0px',
      		opacity: 0.5
		});

		$('.data-equal').equalHeights();
	});
</script>

	<!-- //calendar -->
</body>
</html>
