@extends('admin.layouts.default')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">

                        <h4 class="font-weight-normal mb-3">Total Trainee <i
                                class="mdi mdi-account menu-icon mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ count($totalTrainees) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">

                        <h4 class="font-weight-normal mb-3">Total Trainings Till Now <i
                                class="mdi mdi-bulletin-board mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ count($totalTrainings) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">

                        <h4 class="font-weight-normal mb-3">Total Test Till Now <i
                                class="mdi mdi-content-paste mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">{{ count($totalTests) }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="filterGroup">
            <div class="row align-items-end">
                <div class="col">
                    <div class="form-group ">
                        <label class="mws-form-label">Select Trainings</label>
                        <select class="form-control">
                            <option value="" selected="selected">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group ">
                        <label class="mws-form-label">Select Status</label>
                        <select class="form-control">
                            <option value="" selected="selected">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group ">
                        <label class="mws-form-label">Start Date</label>
                        <input type="text" id="checkInDate" class="form-control" name="" id="">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group ">
                        <label class="mws-form-label">End Date</label>
                        <input type="text" id="checkOutDate" class="form-control" name="" id="">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group ">
                        <label class="mws-form-label">Select Status</label>
                        <select class="form-control">
                            <option value="" selected="selected">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group d-flex gap-2">
                        <button class="btn btn-primary"><i class="fa fa-search me-1"></i> Search</button>
                        <a href="javascript:void(0);" class="btn btn-primary"> <i class="fa fa-refresh "></i>
                            Clear Search</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Overall Training Conduct</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Sr. </th>
                                        <th> Title </th>
                                        <th> Status </th>
                                        <th> Last Update </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 1; ?>
                                    @foreach ($totalTrainings as $training)

                                    <tr>
                                        <td>
                                            {{ $number++ }}
                                        </td>
                                        <td>{{$training->title}} </td>
                                        <td>
                                            <label class="badge badge-gradient-success">DONE</label>
                                        </td>
                                        <td> {{  $training->end_date_time }}</td>
                                        <td class="action-td">
                                            <div class="d-flex gap-2">
                                                <a href="javascript:void(0);" class="btn btn-info" title="View"> <span
                                                        class="fa fa-eye"></span></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left">Visit And Sales Statistics</h4>
                            <div id="visit-sale-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-right">
                            </div>
                        </div>
                        <canvas id="visit-sale-chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Traffic Sources</h4>
                        <canvas id="traffic-chart"></canvas>
                        <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($totalCustomers))
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Trainers</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> First Name </th>
                                            <th> Last Name </th>
                                            <th> Image </th>
                                            <th> Date Of Birth </th>
                                            <th> Joined Date </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($totalCustomers as $customer)
                                            <tr>
                                                <td>
                                                    {{ $customer->first_name }}
                                                </td>
                                                <td>
                                                    {{ $customer->last_name }}

                                                </td>

                                                <td>
                                                    @if ($customer->image != '')
                                                        <img height="30" width="30"
                                                            src="{{ USER_IMAGE_URL . $customer->image }}" />
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $customer->date_of_birth }}
                                                </td>
                                                <td> {{ $customer->created_at }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
    </div>
@endsection
