@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        @can('hotel_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.hotels.create') }}">Add Hotel</a>
        <a class="btn btn-primary hotelImport" href="{{ route('admin.importHotel') }}">Import</a>
        <a class="btn btn-primary " href="/import/SampleHotelImportCSV.csv">Sample CSV File</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Hotels List</li>
        </ol>
      </nav>
    </div>

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Hotels filters</h4>
            <form action="" class="mb-2">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group ">
                            <select class="browser-default custom-select form-control" id="country-dropdown" name="country" required>
                                <option value="101" selected>India</option>
                                {{--@foreach ($Countries as $country)
                                    <option value="{{ $country->id }}" {{!empty(request()->get('country')) && (request()->get('country') == $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach--}}

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group  ">
                            <select class="browser-default custom-select form-control" id="state-dropdown" name="state" required>
                                {{-- @if(!empty(request()->get('state')))
                                @php
                                $statefromdb =   \App\Models\States::where('country_id','=', request()->get('country'))->get();
                                @endphp
                                @foreach ($statefromdb as $stk => $stv )
                                <option value="{{$stv->state_id}}" {{!empty($stv) && request()->get('state')==$stv->state_id ? 'selected' : ''}} >{{$stv->state_name}}</option>
                                @endforeach
                                @else
                                <option selected>State</option>
                                @endif --}}
                                @foreach ($States as $stk => $stv )
                                <option hidden value="">Select State</option>
                                <option value="{{$stv->state_id}}" {{!empty($stv) && request()->get('state')==$stv->state_id ? 'selected' : ''}} >{{$stv->state_name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group ">
                            <select class="browser-default custom-select  form-control" id="city-dropdown" name="city" required>
                                @if(!empty(request()->get('city')))
                                @php
                                $citydata =   \App\Models\Cities::where('state_id','=', request()->get('state'))->get();
                                @endphp
                                @foreach ($citydata as $cityk => $cityv )
                                <option {{!empty($cityv) && request()->get('city')==$cityv->city_name ? 'selected' : ''}} value="{{$cityv->city_name}}">{{$cityv->city_name}}</option>
                                @endforeach
                                @else
                                <option selected>Select City</option>
                                @endif

                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary btn-sm">filter</button>
                        </div>
                    </div>




                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped" id="example">
                <thead>
                    <tr>
                        <th>
                            S.No.
                        </th>

                        <th>
                            {{ trans('cruds.hotel.fields.hotelname') }}
                        </th>
                        {{-- <th>
                            {{ trans('cruds.company.fields.address') }}
                        </th> --}}
                        <th>
                            City
                        </th>

                        <th class="status">
                            <div class="statusInner">
                                Status<br>
                                <span><i>(Active/Inactivate)</i></span>
                            </div>
                        </th>
                        <th class="action">
                            {{ trans('cruds.action.title') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1 @endphp
                    @foreach ($hotel as $key => $hotel)
                    <tr data-entry-id="{{ $hotel->id ?? '' }}">
                        <td>
                            {{ $i ?? '' }}
                        </td>
                    {{-- <td class="py-1">
                        @if ($hotel['primary_image'])
                        <img src="{{ url('hotel\banner') . '/' . $hotel['primary_image'] }}" alt=""
                            style="height:50px">
                        @elseif (isset($hotel['hotel_image']))
                        <img src="{{ $hotel['hotel_image'] }}" alt="" style="height:50px">
                        @endif
                    </td> --}}
                    <td>  {{ $hotel['title'] ?? '' }} </td>
                        {{-- <td>
                        {{ $hotel['address_1'] ?? '' }} {{ $hotel['address_2'] ?? '' }} <br>
                        {{ $hotel['country'] ?? '' }} {{ $hotel['state'] ?? '' }} {{ $hotel['city'] ?? '' }}
                        </td> --}}
                    <td>{{ $hotel['city'] ?? '' }}</td>
                    <td>
                        {{-- <input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Deactivate" class="active-decative" data-id = "{{$hotel['id']}}" {{($hotel['status'] == 1) ? 'checked' : '' }}> --}}
                        @if (isset($hotel['id']))
                            <label class="switch">
                                <input type="checkbox" class="active-decative" data-id="{{ $hotel['id'] }}"
                                    {{ $hotel['status'] == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        @else
                            Active
                        @endif
                    </td>
                    @if (isset($hotel['id']))
                    <td>
                        <div class="actions">
                            @can('hotel_show')
                                <a class="btn btn-xs btn-primary"
                                    href="{{ route('admin.hotels.show', $hotel['id']) }}">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            @endcan

                            @can('hotel_edit')
                                <a class="btn btn-xs btn-info"
                                    href="{{ route('admin.hotels.edit', $hotel['id']) }}">
                                    <i class="mdi mdi-eyedropper-variant"></i>
                                </a>
                            @endcan



                            @can('hotel_delete')
                                <form action="{{ route('admin.hotels.destroy', $hotel['id']) }}" method="POST"
                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                    style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></button>
                                    {{-- <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"> --}}
                                </form>
                            @endcan
                        </div>
                    </td>
                    @else
                    <td>
                        @can('hotel_show')
                        @if(isset($hotel['HotelCode']) && !empty($hotel['HotelCode']))
                        <a class="btn btn-xs btn-primary"
                                href="{{ route('admin.hotels.showapihotel', ['index' => $hotel['ResultIndex'], 'code' => $hotel['HotelCode']]) }}">
                                <i class="mdi mdi-eye"></i>
                            </a>
                        @endif

                        @endcan
                    </td>
                    @endif

                    {{-- <td>
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td> $ 77.99 </td>
                    <td> May 15, 2015 </td> --}}
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

  @endsection
  @section('scripts')

  <script>
    $(document).ready(function() {
        $(".active-decative").change(function() {
            if ($(this).is(":checked") == true) {
                var status = "on"
            } else {
                var status = "off"
            }

            var id = $(this).attr("data-id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.hotels.active-deactive') }}",
                data: {
                    'id': id,
                    'status': status
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    swal("Success!", "Hotel status updated successfully!", "success");
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        /*Country Dropdown Change Event
        $('#country-dropdown').on('change', function() {

            var idCountry = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: "{{ url('admin/fetch-states') }}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#state-dropdown').html(
                        '<option value="">-- Select State --</option>');
                    $.each(result.states, function(key, value) {
                        $("#state-dropdown").append('<option value="' + value
                            .state_id + '">' + value.state_name + '</option>');
                    });
                    $('#city-dropdown').html('<option value="">-- Select City --</option>');
                }
            });
        });
        */
        /*State Dropdown Change Event*/
        $('#state-dropdown').on('change', function() {
            var idState = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url: "{{ url('admin/fetch-cities') }}",
                type: "POST",
                data: {
                    state_id: idState,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#city-dropdown').html('<option value="">Select City</option>');
                    $.each(res.cities, function(key, value) {
                        $("#city-dropdown").append('<option value="' + value
                            .city_name + '">' + value.city_name + '</option>');
                    });
                }
            });
        });

    });
</script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> --}}
<script type="text/javascript">
    $(function () {

    //   var table = $('.yajra-datatable').DataTable({
    //       processing: true,
    //       serverSide: true,
    //       ajax: "{{ route('admin.hotels.index') }}",
    //       columns: [
    //           {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    //           {data: 'title', name: 'title'},
    //           {data: 'city', name: 'city'},
    //           {data: 'state', name: 'state'},
    //           {
    //               data: 'action',
    //               name: 'action',
    //               orderable: true,
    //               searchable: true
    //           },
    //       ]
    //   });

    });
  </script>
  <script>
new DataTable('#example', {
    autoWidth: false,
    aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
    columnDefs: [
        {
            targets: ['3,4,5'],
            className: 'mdc-data-table__cell',
        },
    ],
});
  </script>




  @stop



























