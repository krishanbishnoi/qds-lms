@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        @can('hotel_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.hotels.index') }}">List {{ trans('cruds.hotel.title_singular') }}</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route("admin.hotels.index") }}">Hotel</a></li>
          <li class="breadcrumb-item active" aria-current="page">Show</li>
        </ol>
      </nav>
    </div>

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> Corporate Admin {{ trans('global.list') }}</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.company.fields.id') }}
                            </th>
                            <td>
                                {{ $hotel->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                               Hotel Name
                            </th>
                            <td>
                                {{ $hotel->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.company.fields.address') }}
                            </th>
                            <td>
                                {{ $hotel->address_1 }}, {{$hotel->address_1 }}, {{$hotel->city_name}}, {{$hotel->state_name}}, {{$hotel->country_name}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.company.fields.description') }}
                            </th>
                            <td>
                                {!! $hotel->description !!}
                            </td>
                        </tr>


                        <tr>
                            <th>
                                Primary Image
                            </th>
                            <td>
                                @if(isset($hotel->primary_image))
                                <img src="{{url('hotel\banner').'/'.$hotel->primary_image}}" alt="" style="height:50px">
                                @elseif(isset($hotel->primary_image_1))
                                <img src="{{$hotel->primary_image_1}}" alt="" style="height:50px">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Room Type
                            </th>
                            <td>
                                @if(isset($hotel_room_management))
                                    @foreach ($hotel_room_management as  $roomtype)
                                    <button type="button" class="btn btn-primary"> {{$roomtype->room_type}} </button>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                               Common Amenities
                            </th>
                            <td class="userRolsTD">
                                @if(isset($hotel->common_amenity))
                                    @php
                                        $common_amenity =  explode(",",$hotel->common_amenity);
                                    @endphp
                                    @foreach($common_amenity as  $cma)
                                        <span  class="badge badge-info">{{$cma}}</span>
                                    @endforeach
                                @elseif(isset($hotel->HotelFacilities))
                                    @foreach($hotel->HotelFacilities as  $cma)
                                    <span  class="badge badge-info">{{$cma}}</span>
                                    @endforeach

                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection
