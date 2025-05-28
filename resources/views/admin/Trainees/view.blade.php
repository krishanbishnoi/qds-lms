@extends('admin.layouts.default')
@section('content')
    <script>
        jQuery(document).ready(function() {
            $('#start_from').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_to').datetimepicker({
                format: 'YYYY-MM-DD'
            });

        });
    </script>
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                View {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('admin/dashboard') }}">
                            Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route($modelName . '.index') }}">
                            Users</a>
                    </li>
                    <li class="active"> / View {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-1 align-items-center mb-3">
                            <h3 class="box-title">User Details</h3>
                            <a class="btn btn-primary px-3"
                                href="{{ route('Trainees.report', $model->id) }}">View Overall
                                Report</a>
                        </div>
                        <table class="table table-hover brdrclr" width="100%">
                            <tbody>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Full Name</th>
                                    <td data-th='Name' class="txtFntSze">
                                        {{ isset($model->fullname) ? $model->fullname : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Email</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->email }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">EMPLOYEE ID</th>
                                    <td data-th='Name' class="txtFntSze">
                                        {{ isset($model->olms_id) ? $model->olms_id : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Employee ID</th>
                                    <td data-th='Name' class="txtFntSze">
                                        {{ isset($model->employee_id) ? $model->employee_id : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Mobile Number</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->mobile_number }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Image</th>
                                    <td data-th='Category Name' class="txtFntSze">
                                        @if ($model->image != '')
                                            <img height="50" width="50" src="{{ $model->image }}" />
                                        @endif
                                    </td>
                                </tr>
                                {{--
							<tr>
								<th  width="30%" class="text-right txtFntSze">Last login</th>
								<td data-th='Category Name' class="txtFntSze">{{$model->last_login}}</td>
							</tr>
							--}}
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Bio</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->poi }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Designation</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->designation }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Language</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->languages }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Location</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->location }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Gender</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->gender }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Date of birth</th>
                                    <td data-th='Category Name' class="txtFntSze">{{ $model->date_of_birth }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Registered On</th>
                                    <td data-th='Category Name' class="txtFntSze">
                                        {{ date(Config::get('Reading.date_format'), strtotime($model->created_at)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Status</th>
                                    <td data-th='Status' class="txtFntSze">
                                        @if ($model->is_active == 1)
                                            <span class="label label-success">{{ trans('Activated') }}</span>
                                        @else
                                            <span class="label label-warning">{{ trans('Deactivated') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <!-- <tr>
            <th width="30%" class="text-right txtFntSze">Amount Balance:</th>
            <td data-th='Category Name' class="txtFntSze">
             @if (!empty($model->amount))
    {{ $model->amount }}
@else
    0
    @endif
            </td>
           </tr>
           <tr>
            <th width="30%" class="text-right txtFntSze">Total Amount Won:</th>
            <td data-th='Category Name' class="txtFntSze">
             @if (!empty($won_amount))
    {{ $won_amount }}
@else
    0
    @endif
            </td>
           </tr>
           <tr>
            <th width="30%" class="text-right txtFntSze">Total Amount Invested:</th>
            <td data-th='Category Name' class="txtFntSze">
             @if (!empty($UserInvestAmount))
    {{ $UserInvestAmount }}
@else
    0
    @endif
            </td>
           </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .padding {
            padding-top: 20px;
        }
    </style>
    <script>
        // $('.confirm').on('click', function (e) {
        //         if (confirm($(this).data('confirm'))) {
        //             return true;
        //         }
        //         else {
        //             return false;
        //         }
        //     });

        $(function() {
            $(document).on('click', '.delete_any_item', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to delete this ?",
                    function(result) {
                        if (result) {
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
            $(document).on('click', '.status_any_item', function(e) {


                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to change status ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });
        });
    </script>
@stop
