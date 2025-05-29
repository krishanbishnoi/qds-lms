@extends('layouts.corporate')
@section('content')
    <div class="content">
        <div class="row {{ request()->get('action') == 'add' ? 'showAddEmpForm' : 'hideAddEmpForm' }}">
            <div class="col-lg-12 mb-3">
                <div class="dashboardHeading">
                    <h2 class="mb-4">My Employees</h2>
                    <div class="SubmitDetail box">
                        <h6 class="text-black mb-3 fw-semibold">Add Employees</h6>
                        {{ Form::open(['uri' => route('employees.store'), 'method' => 'post', 'id' => 'addEmployeesForm']) }}
                        <div class="row submitContent">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('first_name', 'Employee First Name', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('first_name', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Albert']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('last_name', 'Employee Last Name', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('last_name', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Parker']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('employee_id', 'Employee ID', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('employee_id', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'HLB00125']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('department', 'Department', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::select('department', ['IT Department' => 'IT Department', 'One' => 'One', 'Two' => 'Two', 'Three' => 'Three'], 'IT Department', ['class' => 'form-select rounded-0 bg-transparent border-0 border-bottom border-dark pe-0']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('designation', 'Designation', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('designation', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Manager']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('phone_number', 'Employee Phone Number', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::number('phone_number', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => '09988776655']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('emp_email', 'Employee Email ID', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::email('emp_email', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'alb.p@xyz.com']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('manager_email', 'Employee Email ID', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::email('manager_email', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Raj@xyz.com']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('location', 'Location', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('location', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Jaipur']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{ Form::label('business_unit', 'Business Unit', ['class' => 'form-label mb-0']) }}
                                    <div class="input-group">
                                        {{ Form::text('business_unit', '', ['class' => 'form-control border-0 border-bottom border-dark ps-0', 'placeholder' => 'Malviya Nagar']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 AddEmployees">
                                {{ Form::submit('Submit', ['class' => 'btn btn-primary my-3 mx-auto']) }}
                                {{-- <p class="text-black mb-0">Lorem Ipsum is simply dummy text of the printing and
                                        typesetting industry.</p> --}}
                                <a href="/import/SampleEmployeesImportCSV.csv" class="my-4 d-block"><u>View Sample</u></a>
                                <button type="button"
                                    class="btn btn-primary d-flex justify-content-center text-center"><span
                                        class="plusicon me-2 "><i class="bi bi-plus"></i></span><a style="color: #fff"
                                        href="{{ route('employees.import') }}">Employees Bulk
                                        Upload</a></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row {{ request()->get('action') != 'add' ? 'showAddEmpList' : 'hideAddEmpList' }}">
            <div class="col-lg-12 ">
                <div class="Employeeslist">
                    <a href="{{ route('employees.index') }}?action=add" class="btn btn-primary mb-3">Add Employee</a>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h6 class="m-0"><img src="../images/staff.png" alt="icon-employee" width="34"
                                    height="34"> Employees List</h6>
                        </div>
                        <div class="input-group w-50 search-responsiveone">
                            <input type="text" class="form-control employee-input position-relative rounded-1"
                                placeholder="search">
                            <span class="position-absolute search-span"><i class="bi bi-search"></i></span>
                        </div>
                        <div class="applyFilter mb-2">
                            <strong class="d-none d-md-block">
                                <span>Apply Filter</span>
                                3 Selected
                            </strong>
                            <button type="button" class="filterToggle" data-bs-toggle="offcanvas"
                                data-bs-target="#filtercanvas" role="button" aria-controls="filtercanvas"><lottie-player
                                    src="../images/filter.json" background="transparent" speed="1"
                                    style="width: 40px; height: 40px;" loop autoplay></lottie-player></button>
                        </div>
                    </div>
                    <!-- <div class="input-group search-responsivemob w-50">
                                                    <input type="text" class="form-control employee-input position-relative rounded-1"
                                                        placeholder="search">
                                                    <span class="position-absolute search-span"><i class="bi bi-search"></i></span>
                                                </div> -->
                    <div class="w-100">
                        <div class="table responsive employeeslistTable upcomingBookingList">
                            <table class="w-100">
                                <thead>
                                    <tr class="position-sticky top-0 mt-0" style="background-color: #67BEC5;">
                                        <th>Employee Name</th>
                                        <th>Id</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Cont. No</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employeesList as $employee)
                                        <tr>
                                            <td>{{ $employee['first_name'] . ' ' . $employee['last_name'] }}</td>
                                            <td>{{ $employee['employee_id'] }}</td>
                                            <td>{{ $employee['department'] }}</td>
                                            <td>{{ $employee['designation'] }}</td>
                                            <td>{{ $employee['phone_number'] }}</td>
                                            <td>{{ $employee['emp_email'] }}</td>
                                            <td>
                                                <form action="{{ route('employees.destroy', $employee['id']) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-xs btn-danger"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="pagination justify-content-end">
                            <button type="button" class="btn-pegination">
                                <i class="bi bi-chevron-left bg-transparent p-0"></i>
                            </button>
                            <ul class="d-flex pagination-page mx-2">
                                <li>
                                    <a href="#" class="page-link border-0 page-link--current">1</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">1</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">2</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">3</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">4</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">5</a>
                                </li>
                                <li>
                                    <a href="#" class="page-link border-0">6</a>
                                </li>
                            </ul>
                            <button type="button" class="btn-pegination">
                                <i class="bi bi-chevron-right bg-transparent p-0"></i>
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-overly"></div>
    <div class="modal fade" id="Submit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered successfullyModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header border-0">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="text-center">
                        <lottie-player src="../images/successfully-send.json" class="mx-auto" background="transparent"
                            speed="1" style="width: 110px; height: 110px;" loop autoplay></lottie-player>
                    </div>
                    <p class="fw-semibold text-black">You have successfully add entity</p>
                    <a href="/employees/?action=add" style="color: #fff"><button class="btn btn-primary w-75">Add
                            More</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end filterSidebar" tabindex="-1" id="filtercanvas"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div class="search-main">
                    <div class="form-group filter_search mb-1">
                        <input type="text" class="form-control"
                            placeholder="Filter by Hotel name, City, State, Flight">
                    </div>
                    <div class="p-3">
                        <div class="form-group mb-3">
                            <label>I'm searching for</label>
                            <ul class="searchingList">
                                <li class="selected"><img src="../images/location-icon-white.svg" alt=""
                                        width="18" height="18">
                                    jaipur <button type="button"><img src="../images/close-white.svg" alt=""
                                            width="16"></button></li>
                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/hotel-icon.svg" alt="" width="18" height="18">
                                        Hotel</a></li>
                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/location-icon1.svg" alt="" width="18"
                                            height="18"> State</a>
                                </li>

                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/flight-icon.svg" alt="" width="18"
                                            height="18"> Fight</a>
                                </li>
                            </ul>
                        </div>
                        <div class="form-group mb-3">
                            <label>Date Range and Time</label>
                            <div class="d-sm-flex gap-3">
                                <input type="text" id="datepicker" class="dateRange" placeholder="20 June - 30 June">
                                <input type="text" id="timepicker" class="timeRange" placeholder="02:00 - 22:00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Recent searches</label>
                            <ul class="recentSearchList">
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Rajasthan<span>India</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Jaipur<span>Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Rajasthan<span>India</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Jaipur<span>Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="searchFilter pt-0" style="display: none;">
                    <div class="form-group filter_search mb-4">
                        <span>City <button type="button" class="categoryToggle"><img src="../images/close-black.svg"
                                    alt=""></button></span>
                        <input type="text" class="form-control" placeholder="Search City">
                    </div>
                    <ul class="recentSearchList">
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Rajasthan<span>India</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Jaipur<span>Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Rajasthan<span>India</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Jaipur<span>Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="formBtn">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="button" class="btn btn-primary">Appy</button>
                </div>
            </form>
        </div>

    </div>
    <style>
        .employeeslistTable div.dataTables_wrapper div.dataTables_filter {
            display: none;
        }
    </style>
@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function() {
            // jQuery('.employeeslistTable table').dataTable({
            //     "pageLength": 10
            // });
            jQuery(document).on('keyup', '.search-responsiveone input.employee-input', function() {
                jQuery('.employeeslistTable div#DataTables_Table_0_filter input[type="search"]').val(jQuery(
                    this).val()).trigger('keyup');
            });

            jQuery(document).on('submit', '#addEmployeesForm', function(e) {
                e.preventDefault();
                var isError = false;
                jQuery('#addEmployeesForm input, #addEmployeesForm select').each(function() {
                    if (!jQuery(this).val() || jQuery(this).val().length < 1) {
                        isError = true;
                        jQuery(this).addClass('invalid_bbc');
                    } else {
                        jQuery(this).removeClass('invalid_bbc');
                    }
                });
                if (isError == false) {
                    var formData = new FormData(this);
                    if (formData) {
                        jQuery.ajax({
                            dataType: 'json',
                            method: 'POST',
                            url: '{{ route('employees.store') }}',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                // alert(response.message);
                                $("#Submit").modal('show');
                                // window.location.replace('/employees');
                            }
                        });
                    }
                }
            });
        });
    </script>
    @parent
@endsection
