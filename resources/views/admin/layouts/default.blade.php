<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@php echo Config::get('Site.title'); @endphp</title>

    <!-- plugins:css -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="{{ asset('css/developer.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('lms-img/qdegrees-fav-icon.png') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('old/css/bootstrap.min.css') }}" > --}}
    <!-- //bootstrap-css -->
    <!-- Custom CSS -->
    <!-- font CSS -->
    <link
        href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic'
        rel='stylesheet' type='text/css'>
    <!-- font-awesome icons -->
    <link rel="stylesheet" href="{{ asset('old/css/font.css') }}" type="text/css" />
    <!-- <link href="{{ asset('old/css/font-awesome.css') }}"   rel="stylesheet"> -->
    <!-- <link href="{{ asset('old/css/font-awesome.min.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />


    <link href="{{ asset('old/css/notification/jquery.toastmessage.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('old/css/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" />


    <link rel="stylesheet" href="{{ asset('old/css/morris.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('old/css/monthly.css') }}">
    <!-- //calendar -->
    <!-- //font-awesome icons -->

    <link href="{{ URL::asset('old/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />
    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>

</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="" href="{{ route('dashboard') }}"><img height="100%" width="100%"
                        src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="logo" /></a>

            </div>
            <br>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->first_name }}
                                    {{ Auth::user()->last_name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ URL('admin/myaccount') }}">
                                <i class="mdi  me-2 text-success"></i> Profile </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ URL('admin/change-password') }}">
                                <i class="mdi  me-2 text-primary"></i> Change Password </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ URL('admin/logout') }}">
                                <i class="mdi me-2 text-primary"></i> Signout </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas sidebarScroll" id="sidebar">
                <ul class="nav">
                    @php
                        $segment1 = Request::segment(1);
                        $segment2 = Request::segment(2);
                        $segment3 = Request::segment(3);
                        $segment4 = Request::segment(4);
                        // dd($segment1, $segment2, $segment3, $segment4);
                    @endphp


                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon    "></i>
                        </a>

                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Users Management</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-account menu-icon "></i>
                        </a>
                        <div class="collapse @php $segment2 ==  'users' ? 'show' : ''; @endphp" id="users">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" @if ($segment2 == 'users') class="active" @endif>
                                    <a class="nav-link" href="{{ route('Trainees.index') }}">All Users</a>
                                </li>
                                <li class="nav-item" @if ($segment2 == 'users' && $segment3 == 'training-managers') class="active" @endif>
                                    <a class="nav-link" href="{{ route('Users.index') }}">Training Managers</a>
                                </li>
                                <li class="nav-item" @if ($segment2 == 'users' && $segment3 == 'trainers') class="active" @endif>
                                    <a class="nav-link" href="{{ route('Trainers.index') }}">Trainers Managers</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <li class="nav-item tests @php echo request()->segment(2) === 'tests' ? 'active' : ''; @endphp">
                        <a class="nav-link" data-bs-toggle="collapse" href="#testManagement" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">{{ trans('Test Management') }}</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-calendar-question menu-icon"></i>
                        </a>
                        <div class="collapse @php echo request()->segment(2) === 'tests' ? 'show' : ''; @endphp"
                            id="testManagement">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('TestCategory.index') }}">Tests Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Test.index') }}">Tests</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Feedback.index') }}">Feedback</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li
                        class="nav-item trainings  @php echo request()->segment(2) === 'trainings' ? 'active' : ''; @endphp">
                        <a class="nav-link" data-bs-toggle="collapse" href="#trainingManagement"
                            aria-expanded="false" aria-controls="ui-basic-training">
                            <span class="menu-title">{{ trans('Training Management') }}</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-bulletin-board menu-icon"></i>
                        </a>
                        <div class="collapse @php echo request()->segment(2) === 'trainings' ? 'show' : ''; @endphp"
                            id="trainingManagement">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('TrainingCategory.index') }}">
                                        {{ trans('Trainings Categories') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Training.index') }}">
                                        {{ trans('Trainings') }}
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('training.add.ai') }}">
                                    {{ trans('Create Training With AI') }}
                                </a>
                                      </li> --}}
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#reportsMgmt" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Reports Management</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-file-check menu-icon "></i>
                        </a>
                        <div class="collapse@php echo request()->segment(3) === 'reports' ? 'active' : ''; @endphp"
                            id="reportsMgmt">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" @if ($segment3 == 'reports' && $segment4 == 'test') class="active" @endif>
                                    <a class="nav-link" href="{{ route('Reports.test') }}">Test Report</a>
                                </li>
                                <li class="nav-item" @if ($segment3 == 'reports' && $segment3 == 'training') class="active" @endif>
                                    <a class="nav-link" href="{{ route('Reports.training') }}">Training Report</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#email" aria-expanded="false"
                                aria-controls="ui-basic">
                                <span class="menu-title">Email Template</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-email menu-icon"></i>
                            </a>
                            <div class="collapse @php echo request()->segment(2) === 'email-manager' ? 'show' : ''; @endphp"
                                id="email">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif>
                                        <a class="nav-link" href="{{ route('EmailTemplate.index') }}">Email
                                            Template</a>
                                    </li>
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif>
                                        <a class="nav-link" href="{{ route('EmailLogs.listEmail') }}">Email Logs</a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link @php echo request()->segment(2) === 'masters' ? 'active' : ''; @endphp"
                            data-bs-toggle="collapse" href="#Masters" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Masters Management</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                        </a>
                        <div class="collapse @php echo request()->segment(2) === 'masters' ? 'show' : ''; @endphp"
                            id="Masters">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" @if ($segment1 == 'lobs' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Lob.index') }}">LOB Management</a></li>
                                <li class="nav-item" @if ($segment1 == 'designation' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Designation.index') }}">Designation
                                        Management</a></li>
                                <li class="nav-item" @if ($segment1 == 'regions' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Region.index') }}">Region Management</a>
                                </li>
                                <li class="nav-item" @if ($segment1 == 'circles' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Circle.index') }}">Circle Management</a>
                                </li>
                                <li class="nav-item" @if ($segment1 == 'training-types' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('TrainingType.index') }}">TrainingType
                                        Management</a></li>
                                <li class="nav-item" @if ($segment1 == 'partners' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Partner.index') }}">Partner Management</a>
                                </li>
                                <li class="nav-item" @if ($segment1 == 'domains' && $segment3 == 'Site') class="active" @endif> <a
                                        class="nav-link" href="{{ route('Domain.index') }}">Domain Management</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#cms" aria-expanded="false"
                                aria-controls="ui-basic">
                                <span class="menu-title">Page Management</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                            </a>
                            <div class="collapse" id="cms">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif> <a
                                            class="nav-link" href="{{ route('Cms.index') }}">Cms Page Management</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#settings" aria-expanded="false"
                                aria-controls="ui-basic">
                                <span class="menu-title">Settings</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-settings
                            menu-icon"></i>
                            </a>
                            <div class="collapse" id="settings">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif> <a
                                            class="nav-link" href="{{ URL('admin/settings/prefix/Site') }}">Site
                                            Setting</a></li>
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif> <a
                                            class="nav-link"
                                            href="{{ URL('admin/settings/prefix/Reading') }}">Reading Setting</a></li>
                                    <!-- <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif> <a class="nav-link"  href="{{ URL('admin/settings/prefix/Social') }}">Social Setting</a></li> -->
                                    <li class="nav-item" @if ($segment1 == 'settings' && $segment3 == 'Site') class="active" @endif> <a
                                            class="nav-link"
                                            href="{{ URL('admin/settings/prefix/Contact') }}">Contect Setting</a></li>
                                </ul>
                            </div>
                        </li>
                    @endif

                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">

                @if (Session::has('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            show_message(@json(Session::get('error')), 'error');
                        });
                    </script>
                @endif

                @if (Session::has('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            show_message(@json(Session::get('success')), 'success');
                        });
                    </script>
                @endif

                @if (Session::has('flash_notice'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            show_message(@json(Session::get('flash_notice')), 'success');
                        });
                    </script>
                @endif

                @yield('content')

                <script>
                    function show_message(message, message_type) {
                        $().toastmessage('showToast', {
                            text: message,
                            sticky: false,
                            position: 'top-right',
                            type: message_type,
                        });
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('.delete_any_item').forEach(function(el) {
                            el.addEventListener('click', function(e) {
                                e.preventDefault();
                                let url = this.getAttribute('href');
                                bootbox.confirm("Are you sure want to delete this ?", function(result) {
                                    if (result) {
                                        window.location.replace(url);
                                    }
                                });
                            });
                        });

                        document.querySelectorAll('.status_any_item').forEach(function(el) {
                            el.addEventListener('click', function(e) {
                                e.preventDefault();
                                let url = this.getAttribute('href');
                                bootbox.confirm("Are you sure want to change status ?", function(result) {
                                    if (result) {
                                        window.location.replace(url);
                                    }
                                });
                            });
                        });
                    });
                </script>
            </div>

            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ URL::asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ URL::asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ URL::asset('js/off-canvas.js') }}"></script>
    {{-- <script src="{{ URL::asset('js/hoverable-collapse.js') }}"></script> --}}
    <script src="{{ URL::asset('js/misc.') }}js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ URL::asset('js/dashboard.js') }}"></script>
    <script src="{{ URL::asset('js/todolist.js') }}"></script>

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    <script src="{{ URL::asset('old/js/jquery2.0.3.min.js') }}"></script>
    <script src="{{ URL::asset('old/js/formValidation.js') }}"></script>
    <script src="{{ URL::asset('old/js/bootbox.js') }}"></script>
    {{-- <script src="{{ URL::asset('old/js/framework/bootstrap.js') }}" ></script> --}}

    <script src="{{ URL::asset('old/js/raphael-min.js') }}"></script>
    <script src="{{ URL::asset('old/js/morris.js') }}"></script>
    <script src="{{ URL::asset('old/js/moment.js') }}"></script>
    <script src="{{ URL::asset('old/js/bootstrap-datetimepicker.js') }}"></script>

    <script src="{{ URL::asset('old/css/notification/jquery.toastmessage.js') }}"></script>
    <!-- End custom js for this page -->

</body>

</html>
<style>
    .error-message {
        color: red;
    }

    .help-inline {
        color: red;
    }

    .pull-right {
        float: right !important;
    }
</style>
