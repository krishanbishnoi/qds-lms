@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        @can('booking_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.booking-manager.create') }}"> Create Booking</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item">Bookings</li>
        </ol>
      </nav>
    </div>

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> {{ trans('cruds.allhotel.fields.bedtype') }} {{ trans('global.list') }}</h4>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-all"
                        type="button" role="tab" aria-controls="all" aria-selected="true">All Bookings</button>
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#nav-completed"
                        type="button" role="tab" aria-controls="completed" aria-selected="false">Completed
                        bookings</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-on-going"
                        type="button" role="tab" aria-controls="nav-on-going" aria-selected="false">On-going
                        bookings</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-upcoming"
                        type="button" role="tab" aria-controls="nav-upcoming" aria-selected="false">Upcoming
                        bookings</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-booking-request"
                        type="button" role="tab" aria-controls="nav-booking-request" aria-selected="false">Booking
                        request</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Company">
                            <thead>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Booking ID
                                    </th>
                                    <th>
                                        Corporate Admin
                                    </th>
                                    <th class="hotel">
                                        Hotel
                                    </th>
                                    <th>
                                        Room Type
                                    </th>
                                    <th>
                                        Check In/Out
                                    </th>
                                    <th class="status">
                                        Status
                                    </th>
                                    <th class="action">
                                        {{ trans('cruds.action.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($Bookings as $key => $Booking)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>TRAWBOOKING-{{ $Booking['id'] }}</td>
                                        <td>{{ $Booking['cop_admin'] }}</td>
                                        <td>{{ $Booking['hotelId'] }}</td>
                                        <td>{{ $Booking['roomId'] }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($Booking['checkIn'])) . ' To ' . date('d M Y H:i', strtotime($Booking['checkOut'])) }}
                                        </td>
                                        <td>{{ $Booking['status'] }}</td>
                                        <td>
                                            <div class="actions actionBtnGroup">
                                                @can('hotel_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('admin.booking-manager.show', $Booking['id']) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endcan

                                                @can('company_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('admin.booking-manager.edit', $Booking['id']) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan



                                                @can('company_delete')
                                                    <form action="{{ route('admin.booking-manager.destroy', $Booking['id']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                                        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Company">
                            <thead>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Booking ID
                                    </th>
                                    <th>
                                        Corporate Admin
                                    </th>
                                    <th class="hotel">
                                        Hotel
                                    </th>
                                    <th>
                                        Room Type
                                    </th>
                                    <th>
                                        Check In/Out
                                    </th>
                                    <th class="status">
                                        Status
                                    </th>
                                    <th class="action">
                                        {{ trans('cruds.action.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($BookingsCompleted as $key => $Booking)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>TRAWBOOKING-{{ $Booking['id'] }}</td>
                                        <td>{{ $Booking['cop_admin'] }}</td>
                                        <td>{{ $Booking['hotelId'] }}</td>
                                        <td>{{ $Booking['roomId'] }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($Booking['checkIn'])) . ' To ' . date('d M Y H:i', strtotime($Booking['checkOut'])) }}
                                        </td>
                                        <td>{{ $Booking['status'] }}</td>
                                        <td>
                                            <div class="actions">
                                                @can('hotel_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('admin.booking-manager.show', $Booking['id']) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endcan

                                                @can('booking_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('admin.booking-manager.edit', $Booking['id']) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan



                                                @can('booking_delete')
                                                    <form
                                                        action="{{ route('admin.booking-manager.destroy', $Booking['id']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                                        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="nav-on-going" role="tabpanel" aria-labelledby="nav-on-going">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Company">
                            <thead>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Booking ID
                                    </th>
                                    <th>
                                        Corporate Admin
                                    </th>
                                    <th class="hotel">
                                        Hotel
                                    </th>
                                    <th>
                                        Room Type
                                    </th>
                                    <th>
                                        Check In/Out
                                    </th>
                                    <th class="status">
                                        Status
                                    </th>
                                    <th class="action">
                                        {{ trans('cruds.action.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($BookingsOnGoing as $key => $Booking)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>TRAWBOOKING-{{ $Booking['id'] }}</td>
                                        <td>{{ $Booking['cop_admin'] }}</td>
                                        <td>{{ $Booking['hotelId'] }}</td>
                                        <td>{{ $Booking['roomId'] }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($Booking['checkIn'])) . ' To ' . date('d M Y H:i', strtotime($Booking['checkOut'])) }}
                                        </td>
                                        <td>{{ $Booking['status'] }}</td>
                                        <td>
                                            <div class="actions">
                                                @can('booking_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('admin.booking-manager.show', $Booking['id']) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endcan

                                                @can('booking_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('admin.booking-manager.edit', $Booking['id']) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan



                                                @can('booking_delete')
                                                    <form
                                                        action="{{ route('admin.booking-manager.destroy', $Booking['id']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                                        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Company">
                            <thead>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Booking ID
                                    </th>
                                    <th>
                                        Corporate Admin
                                    </th>
                                    <th class="hotel">
                                        Hotel
                                    </th>
                                    <th>
                                        Room Type
                                    </th>
                                    <th>
                                        Check In/Out
                                    </th>
                                    <th class="status">
                                        Status
                                    </th>
                                    <th class="action">
                                        {{ trans('cruds.action.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($BookingsUpcoming as $key => $Booking)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>TRAWBOOKING-{{ $Booking['id'] }}</td>
                                        <td>{{ $Booking['cop_admin'] }}</td>
                                        <td>{{ $Booking['hotelId'] }}</td>
                                        <td>{{ $Booking['roomId'] }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($Booking['checkIn'])) . ' To ' . date('d M Y H:i', strtotime($Booking['checkOut'])) }}
                                        </td>
                                        <td>{{ $Booking['status'] }}</td>
                                        <td>
                                            <div class="actions">
                                                @can('hotel_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('admin.booking-manager.show', $Booking['id']) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endcan

                                                @can('company_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('admin.booking-manager.edit', $Booking['id']) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan



                                                @can('company_delete')
                                                    <form
                                                        action="{{ route('admin.booking-manager.destroy', $Booking['id']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                                        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-booking-request" role="tabpanel"
                    aria-labelledby="nav-booking-request">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Company">
                            <thead>
                                <tr>
                                    <th>
                                        S.No.
                                    </th>
                                    <th>
                                        Booking ID
                                    </th>
                                    <th>
                                        Corporate Admin
                                    </th>
                                    <th class="hotel">
                                        Hotel
                                    </th>
                                    <th>
                                        Room Type
                                    </th>
                                    <th>
                                        Check In/Out
                                    </th>
                                    <th class="status">
                                        Status
                                    </th>
                                    <th class="action">
                                        {{ trans('cruds.action.title') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1 @endphp
                                @foreach ($BookingsRequest as $key => $Booking)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>TRAWBOOKING-{{ $Booking['id'] }}</td>
                                        <td>{{ $Booking['cop_admin'] }}</td>
                                        <td>{{ $Booking['hotelId'] }}</td>
                                        <td>{{ $Booking['roomId'] }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($Booking['checkIn'])) . ' To ' . date('d M Y H:i', strtotime($Booking['checkOut'])) }}
                                        </td>
                                        <td>{{ $Booking['status'] }}</td>
                                        <td>
                                            <div class="actions">
                                                @can('hotel_show')
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('admin.booking-manager.show', $Booking['id']) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endcan

                                                @can('company_edit')
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('admin.booking-manager.edit', $Booking['id']) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @endcan



                                                @can('company_delete')
                                                    <form
                                                        action="{{ route('admin.booking-manager.destroy', $Booking['id']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                        style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-xs btn-danger"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                                        {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection
