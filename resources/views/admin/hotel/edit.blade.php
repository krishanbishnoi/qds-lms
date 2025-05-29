@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.hotels.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route("admin.hotels.index") }}">Hotel</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <form id="hotelForm" action="{{ route('admin.hotels.update', [$hotel->id]) }}" method="POST"
                enctype="multipart/form-data" class="form-padding">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $hotel->id }}">
                <div class="first-step">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <b>Hotel Details</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="name">{{ trans('cruds.company.fields.name') }}*</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ old('name', isset($hotel) ? $hotel->title : '') }}">
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('name'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="email">{{ trans('cruds.hotel.fields.email') }}*</label>
                                    <input type="email" id="address" name="email" class="form-control"
                                        value="{{ old('email', isset($hotel) ? $hotel->hotel_email : '') }}">
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('address'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="contact">{{ trans('cruds.hotel.fields.contact') }}*</label>
                                    <input type="number" id="address" name="contact" class="form-control"
                                        value="{{ old('contact', isset($hotel) ? $hotel->contact : '') }}">
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('contact'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('contact') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                        @if (!empty($hotel->primary_image))
                            <div class="col-sm-1">
                                <img src="{{ url('hotel\banner') . '/' . $hotel->primary_image }}" alt=""
                                    style="height:36px">
                                <input type="hidden" name="primary_image_prv"
                                    value="{{ old('email', isset($hotel) ? $hotel->primary_image : '') }}">
                                <span class="error"> </span>
                            </div>
                        @endif
                        <div class="col-sm-5">
                            <div class="form-group {{ $errors->has('banner_image') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="banner_image">Primary Image (Max size:2MB)</label>
                                    <input accept="image/png, image/jpeg, image/jpg" type="file" name="primary_image"
                                        class="form-control"
                                        value="">
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('banner_image'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('banner_image') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <div class="grp-f">
                            <label for="description">{{ trans('cruds.hotel.fields.description') }}*</label>
                            <textarea class="form-control" class="form-control" name="description" value="">{{ old('email', isset($hotel) ? $hotel->description : '') }}</textarea>
                            <span class="error"> </span>
                        </div>
                        @if ($errors->has('description'))
                            <em class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                    <div class="form-group hotelGalleryImage {{ $errors->has('gallery') ? 'has-error' : '' }}">
                        <label for="gallery">{{ trans('cruds.hotel.fields.gallery') }}</label>
                        <i>(Max image size 5MB)</i>
                        <div class="needsclick dropzone" id="gallery-dropzone">
                            <div class="dz-message" data-dz-message><span>Click/Drop files here to upload<br><i>Up to 9
                                        images in gallery<br> (Image size- 5MB)</i></span></div>
                        </div>
                        @if ($errors->has('gallery'))
                            <em class="invalid-feedback">
                                {{ $errors->first('gallery') }}
                            </em>
                        @endif
                        {{-- <p class="helper-block">
                            {{ trans('cruds.company.fields.gallery_helper') }}
                        </p> --}}
                        <span class="error"> </span>
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Time for check in</label>
                                <input type="time" name="check_in" class="form-control"
                                    value="{{ old('check_in', isset($hotel) ? $hotel->check_in_time : '') }}">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Time for check Out</label>
                                <input type="time" name="check_out" class="form-control"
                                    value="{{ old('check_out', isset($hotel) ? $hotel->check_out_time : '') }}">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="amenity"> Select Common Amenities*

                                    <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip" data-placement="right"
                                        title="Add new amenities from amenities section" aria-hidden="true"></i></label>
                                <select class="form-control select2" name="amenity_common[]" multiple="multiple">
                                    @if (!empty($commonAmenity))
                                        @foreach ($commonAmenity as $key => $common)
                                            @php $common_amenity = explode(",",$hotel->common_amenity) @endphp
                                            <option value="{{ $common->slug }}"
                                                {{ !empty($common) && in_array($common->slug, $common_amenity) ? 'selected' : '' }}>
                                                {{ strlen($common->title) > 20 ? substr($common->title, 0, 20) . '...' : $common->title }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="error"> </span>
                                @if ($errors->has('amenity'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('amenity') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="amenity">Status*</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ $hotel->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $hotel->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('amenity'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('amenity') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <b>Room Details</b>
                            <hr>
                        </div>
                    </div>

                    <div class="col-md-12 append-amenty">
                        @if (!empty($roommanagement) && count($roommanagement) > 0)
                            @php $i = 1; @endphp
                            @foreach ($roommanagement as $krm => $rmkye)
                                <div class="row roomRow">
                                    <div class="roomNumbers">Room : <span class="rNo"></span></div>
                                    <div class="removeRoom"><i class="mdi mdi-close-circle-outline" aria-hidden="true"></i></div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="address">{{ trans('cruds.hotel.fields.room_type') }}</label>
                                            @if ($krm == 0)
                                                <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                                    data-placement="right"
                                                    title="Add new room type from room type section"
                                                    aria-hidden="true"></i>
                                            @endif
                                            <select class="roomType form-control"
                                                name="hotel[{{ $i }}][roomtype]">
                                                @if (!empty($roomtype))
                                                    @foreach ($roomtype as $key => $room)
                                                        <option value="{{ $room->slug }}"
                                                            {{ !empty($room) && $room->slug == $rmkye->room_type ? 'selected' : '' }}>
                                                            {{ strlen($room->title) > 20 ? substr($room->title, 0, 20) . '...' : $room->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="error"> </span>
                                            @if ($errors->has('address'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('address') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <label for="amenity"> Select Amenities*</label>
                                            @if ($krm == 0)
                                                <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                                    data-placement="right"
                                                    title="Add new amenities from amenities section"
                                                    aria-hidden="true"></i>
                                            @endif
                                            <select class="form-control select2"
                                                name="hotel[{{ $i }}][amenity][]" multiple="multiple">
                                                @if (!empty($Amenity))
                                                    @foreach ($Amenity as $key => $am)
                                                        @php $amenity = explode(",",$rmkye->amenity) @endphp
                                                        <option value="{{ $am->slug }}"
                                                            {{ !empty($amenity) && in_array($am->slug, $amenity) ? 'selected' : '' }}>
                                                            {{ strlen($am->title) > 20 ? substr($am->title, 0, 20) . '...' : $am->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('amenity'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('amenity') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.name_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="bed_type">Bed Type*</label>
                                            @if ($krm == 0)
                                                <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                                    data-placement="right" title="Add new bed type from bed type section"
                                                    aria-hidden="true"></i>
                                            @endif
                                            <select class="form-control select2"
                                                name="hotel[{{ $i }}][bed_type][]" multiple="multiple">
                                                @if (!empty($bedtype))
                                                    @foreach ($bedtype as $key => $bedty)
                                                        @php $bed_type = explode(",",$rmkye->bed_type) @endphp
                                                        <option value="{{ $bedty->slug }}"
                                                            {{ !empty($bed_type) && in_array($bedty->slug, $bed_type) ? 'selected' : '' }}>
                                                            {{ strlen($bedty->title) > 20 ? substr($bedty->title, 0, 20) . '...' : $bedty->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('bed_type'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('price') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="no_room">No. of Rooms*</label>
                                            <input type="number" id="room"
                                                name="hotel[{{ $i }}][no_room]" class="form-control"
                                                value="{{ old('no_room', isset($rmkye) ? $rmkye->no_of_room : '') }}">
                                            @if ($errors->has('room'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('room') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="price">{{ trans('cruds.hotel.fields.price') }}*</label>
                                            <input type="number" id="address"
                                                name="hotel[{{ $i }}][rprice]" class="form-control"
                                                value="{{ old('price', isset($rmkye) ? $rmkye->r_price : '') }}">
                                            @if ($errors->has('price'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('price') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="price">{{ trans('cruds.hotel.fields.sprice') }}*</label>
                                            <input type="number" id="address"
                                                name="hotel[{{ $i }}][sprice]" class="form-control"
                                                value="{{ old('price', isset($rmkye) ? $rmkye->s_price : '') }}">
                                            @if ($errors->has('price'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('price') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <label for="occupancy">Occupancy (Per room)*</label>
                                            <input type="number" id="occupancy"
                                                name="hotel[{{ $i }}][occupancy]" class="form-control"
                                                value="{{ old('occupancy', isset($rmkye) ? $rmkye->occupancy : '') }}">
                                            @if ($errors->has('occupancy'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('occupancy') }}
                                                </em>
                                            @endif
                                            {{-- <p class="helper-block">
                                                {{ trans('cruds.company.fields.address_helper') }}
                                            </p> --}}
                                            <span class="error"> </span>
                                        </div>
                                    </div>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        @else
                            <div class="row roomRow">
                                <div class="roomNumbers">Room : <span class="rNo"></span></div>
                                <div class="removeRoom"><i class="mdi mdi-close-circle-outline" aria-hidden="true"></i></div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="roomtype">{{ trans('cruds.hotel.fields.room_type') }}</label>
                                        <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                            data-placement="right" title="Add new room type from room type section"
                                            aria-hidden="true"></i>
                                        <select class="form-control" name="hotel[1][roomtype]" id="roomtype">
                                            @if (!empty($roomtype))
                                                @foreach ($roomtype as $key => $room)
                                                    <option value="{{ $room->slug }}">
                                                        {{ strlen($room->title) > 20 ? substr($room->title, 0, 20) . '...' : $room->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('roomtype'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('roomtype') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="amenity"> Select Amenities*</label>
                                        <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                            data-placement="right" title="Add new amenities from amenities section"
                                            aria-hidden="true"></i>
                                        <select class="form-control select2" name="hotel[1][amenity][]"
                                            multiple="multiple">
                                            @if (!empty($Amenity))
                                                @foreach ($Amenity as $key => $am)
                                                    <option value="{{ $am->slug }}">
                                                        {{ strlen($am->title) > 20 ? substr($am->title, 0, 20) . '...' : $am->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('amenity'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('amenity') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.name_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="price">Bed Type</label>
                                        <i class="mdi mdi-close-circle-outline info-icon" data-toggle="tooltip"
                                            data-placement="right" title="Add new bed type from bed type section"
                                            aria-hidden="true"></i>
                                        <select class="form-control select2" name="hotel[1][bed_type][]"
                                            multiple="multiple">
                                            @if (!empty($bedtype))
                                                @foreach ($bedtype as $key => $bedty)
                                                    <option value="{{ $bedty->slug }}">
                                                        {{ strlen($bedty->title) > 20 ? substr($bedty->title, 0, 20) . '...' : $bedty->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('price'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('price') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="price">No. of Rooms</label>
                                        <input type="number" id="room" name="hotel[1][no_room]"
                                            class="form-control">
                                        @if ($errors->has('room'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('room') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="price">{{ trans('cruds.hotel.fields.price') }}</label>
                                        <input type="number" id="address" name="hotel[1][rprice]"
                                            class="form-control">
                                        @if ($errors->has('price'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('price') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="price">{{ trans('cruds.hotel.fields.sprice') }}</label>
                                        <input type="number" id="address" name="hotel[1][sprice]"
                                            class="form-control">
                                        @if ($errors->has('price'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('price') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="occupancy">Occupancy (Per room)*</label>
                                        <input type="number" id="occupancy" name="hotel[1][occupancy]"
                                            class="form-control">
                                        @if ($errors->has('occupancy'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('occupancy') }}
                                            </em>
                                        @endif
                                        {{-- <p class="helper-block">
                                            {{ trans('cruds.company.fields.address_helper') }}
                                        </p> --}}
                                        <span class="error"> </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-sm-12">
                            <button class="btn  btn-primary float-right add-amenities" type="button" id="add-amenities"
                                style="background-color: #20a8d8">Add More Rooms</button>
                        </div>
                    </div>
                    <div>
                        <input class="btn btn-primary float-right step-1" type="button" value="Next Step">
                    </div>
                </div>

                <div class="second-step mt-3">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <b>Address Details</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="address">{{ trans('cruds.hotel.fields.address') }}</label>
                                    <textarea id="address" name="address" class="requiredField form-control min-hight-update requiredField">{{ old('address', isset($hotel) ? $hotel->address_1 : '') }}</textarea>
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('address'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('address') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="address">{{ trans('cruds.hotel.fields.address1') }}</label>
                                    <textarea id="address" name="address_2" class="form-control min-hight-update">{{ old('address_2', isset($hotel) ? $hotel->address_2 : '') }}</textarea>
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('address'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('address') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="country">Select {{ trans('cruds.hotel.fields.country') }}</label>
                                    <select class="form-control requiredField" name="country" id="country-dropdown">
                                        @if (!empty($Countries))
                                            <option hidden value="">Select</option>
                                            @foreach ($Countries as $val)
                                                <option value="{{ $val->id }}"
                                                    {{ !empty($hotel->country) && $hotel->country == $val->id ? 'selected' : '' }}>
                                                    {{ $val->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('country'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('country') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="state">Select {{ trans('cruds.hotel.fields.state') }}</label>
                                    <select class="form-control requiredField" name="state" id="state-dropdown">
                                        <option value="{{ $state->state_id ?? '' }}" selected>{{ $state->state_name ?? '' }}
                                        </option>
                                    </select>
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('state'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('state') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="city">Select {{ trans('cruds.hotel.fields.city') }}</label>
                                    <select class="form-control requiredField" name="city" id="city-dropdown">
                                        <option value="{{ $city->city_id ?? '' }}" selected>{{ $city->city_name ?? '' }}</option>
                                    </select>
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('city'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('city') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="pincode">Pincode</label>
                                    <input type="number" class="requiredField form-control requiredField" name="pincode"
                                        value="{{ old('pincode', isset($hotel) ? $hotel->pincode : '') }}">
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('pincode'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('pincode') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="landmark">Landmark</label>
                                    <input type="text" class="form-control" name="landmark"
                                        value="{{ old('landmark', isset($hotel) ? $hotel->landmark : '') }}">
                                    <span class="error"></span>
                                </div>
                                @if ($errors->has('landmark'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('landmark') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.address_helper') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input class="btn btn-primary  next action-button prev-2" type="button" value="Previous">
                        <input class="btn btn-primary float-right step-2" type="button" value="Next Step">
                    </div>
                </div>
                <div class="thired-step mt-3">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <b>Account Details</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Account Number</label>
                                <input type="number" name="account_number" class="form-control requiredField"
                                    value="{{ old('account_number', isset($hotel) ? $hotel->ac_number : '') }}">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Account Holder Name</label>
                                <input type="text" name="holder_name" class="form-control requiredField"
                                    value="{{ old('holder_name', isset($hotel) ? $hotel->ac_number : '') }}" onkeydown="return /[a-zA-Z]/i.test(event.key)">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc" class="form-control requiredField"
                                    value="{{ old('ifsc', isset($hotel) ? $hotel->ac_ifsc : '') }}" >
                                <span class="error" > </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>GST Number </label>
                                <input type="text" name="gst_number" class="form-control requiredField"  value="{{ old('gst_number', isset($hotel) ? $hotel->gst_number : '') }}">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                    </div>
                    <div>
                        <input class="btn btn-primary  prev-3" type="button" value="Previous">
                        <input class="btn btn-primary float-right step-3" type="button" value="Next Step">
                    </div>
                </div>

                <div class="forth-step mt-3">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <b>SEO</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" class="form-control"
                                    value="{{ old('meta_title', isset($hotel) ? $hotel->meta_title : '') }}">
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Meta Keyword</label>
                                <input type="text" name="meta_keyword" class="form-control"
                                    value="{{ old('meta_keyword', isset($hotel) ? $hotel->meta_keyword : '') }}">
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-12">
                            <div class="grp-f">
                                <label>Meta Description</label>
                                <textarea type="text" name="meta_description" class="form-control" value="">{{ old('meta_description', isset($hotel) ? $hotel->meta_description : '') }}</textarea>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>

                    </div>
                    <div>
                        <input class="btn btn-primary  prev-4" type="button" value="Previous">
                        <input class="btn btn-danger float-right" type="submit" value="{{ trans('global.update') }}">
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script>
        $(document).ready(function() {

            jQuery('input#contact').keyup(function(e){
                if(jQuery(this).val() < 0){
                    jQuery(this).val(0);
                }
            });

            $('#hotelForm').on('submit', function(e) {
                e.preventDefault();
                // var formData = jQuery(this).serialize();
                var formData = new FormData(this);
                if (formData) {
                    jQuery.ajax({
                        dataType: 'json',
                        method: 'POST',
                        url: '{{ route('admin.hotels.update', [$hotel->id]) }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            alert(response.message);
                            window.location.replace('/admin/hotels/');
                        }
                    });
                }
            });

            reOrderRoomNumber();
            checkRoomRemovable();

            jQuery(document).on('click','.roomRow .removeRoom',function(){
                jQuery(this).closest('.roomRow').remove();
                reOrderRoomNumber();
                checkRoomRemovable()
            });

        });
        function reOrderRoomNumber(){
            var i = 1;
            jQuery(".row.roomRow").each(function(){
                jQuery(this).find(".rNo").html(i);
                i++;
            });
        }

        function checkRoomRemovable(){
            if(jQuery('.roomRow').length < 2){
                jQuery('.row.roomRow:nth-child(1) .removeRoom').hide();
            }else{
                jQuery('.row.roomRow:nth-child(1) .removeRoom').show();
            }
        }
    </script>
    <script>
        Dropzone.options.galleryDropzone = {
            url: '{{ route('admin.hotels.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 9,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form#hotelForm').find('input[name="gallery"]').remove()
                $('form#hotelForm').append('<input type="hidden" name="gallery[]" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form#hotelForm').find('input[value="' + file.file_name + '"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($hotelMedia))
                    var files = {!! json_encode($hotelMedia) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.original_url.replace('http://localhost/',
                            '/'))
                        file.previewElement.classList.add('dz-complete')
                        $('form#hotelForm').append('<input type="hidden" name="gallery[]" value="' + file
                            .file_name + '">')
                        this.options.maxFiles = this.options.maxFiles - 1
                    }
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                if (message == 'You can not upload any more files.') {
                    message = 'You cannot upload more than 9 images'
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function($) {
            //     $('.myCkeditor').ckeditor( {
            //     toolbar: ["undo", "redo", "bold", "italic", "blockQuote", "ckfinder", "imageTextAlternative", "imageUpload", "heading", "imageStyle:full", "imageStyle:side", "link", "numberedList", "bulletedList", "mediaEmbed", "insertTable", "tableColumn", "tableRow", "mergeTableCells"]
            // });
            tinymce.init({
                selector: '.myCkeditor'
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            /*------------------------------------------
            --------------------------------------------
            Country Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
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

            /*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
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
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');
                        $.each(res.cities, function(key, value) {
                            $("#city-dropdown").append('<option value="' + value
                                .city_id + '">' + value.city_name + '</option>');
                        });
                    }
                });
            });

        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            jQuery(document).on('change','.row.roomRow select.roomType',function(){
                var isMultipleRoomtype = false;
                var preRoomType = [];
                jQuery(".row.roomRow select.roomType").each(function(){
                    var roomType = jQuery(this).val();
                    if(preRoomType.includes(roomType)){
                        jQuery(this).css('border','1px solid red').closest('div').find('span.error').html('Room type already selected.');
                        isStepFirstError = true;
                    }else{
                        jQuery(this).css('border','none').closest('div').find('span.error').html('');
                    }
                    preRoomType.push(roomType);
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $(".first-step").show();
            $(".second-step").hide();
            $(".thired-step").hide();
            $(".forth-step").hide();
            $(function() {
                $(document).on('click', ".step-1", function() {

                    jQuery('.helper-block,.invalid-feedback').each(function() {
                        jQuery(this).hide()
                    });

                    var isStepFirstError = false;
                    var stepFirstscrollTo = false;
                    jQuery(
                            '.first-step input.form-control, .first-step select, .first-step textarea'
                        )
                        .each(
                            function() {
                                if ((!jQuery(this).val() || jQuery(this).val().length < 1) &&
                                    jQuery(this).attr('name') != 'primary_image') {
                                    isStepFirstError = true;
                                    jQuery(this).closest('div').find('.error').html(
                                        'This field is required.');
                                    if (!stepFirstscrollTo) {
                                        stepFirstscrollTo = true;
                                        document.querySelector('body main #' + jQuery(this).attr(
                                            'id'))?.scrollIntoView({
                                            behavior: 'smooth'
                                        });
                                    }
                                } else {
                                    jQuery(this).closest('div').find('.error').html('');
                                }
                            });

                    var hotelGalleryImage = jQuery('input[name="gallery[]"]').length;
                    if (hotelGalleryImage < 2) {
                        isStepFirstError = true;
                        if (!stepFirstscrollTo) {
                            document.querySelector('.hotelGalleryImage')?.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                        jQuery('.hotelGalleryImage span.error').html(
                            'Minimum 2 images are required.');
                    } else if (hotelGalleryImage > 9 || jQuery(
                            '.dz-preview.dz-error.dz-complete.dz-image-preview').length > 9) {
                        isStepFirstError = true;
                        if (!stepFirstscrollTo) {
                            document.querySelector('.hotelGalleryImage')?.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                        jQuery('.hotelGalleryImage span.error').html(
                            'You can not upload more than 9 images.');
                    } else if (hotelGalleryImage >= 2) {
                        jQuery('.hotelGalleryImage span.error').html('');
                    }

                    if (!jQuery('input[name="primary_image_prv"]').val() && !jQuery(
                            'input[name="primary_image"]').val()) {
                        isStepFirstError = true;
                        jQuery('input[name="primary_image"]').closest('div').find('.error').html(
                            'This field is required.');
                    } else if (jQuery('input[name="primary_image"]').val()) {
                        var file = jQuery(
                            'input[name="primary_image"]').val();
                        var extension = file.substr((file.lastIndexOf('.') + 1));
                        if (extension != 'png' && extension != 'jpg' && extension != 'jpeg') {
                            isStepFirstError = true;
                            jQuery('input[name="primary_image"]').closest('div').find('.error')
                                .html('Please select a valid image format.');
                        }
                    }

                    var isMultipleRoomtype = false;
                    var preRoomType = [];
                    jQuery(".row.roomRow select.roomType").each(function() {
                        var roomType = jQuery(this).val();
                        if (preRoomType.includes(roomType)) {
                            jQuery(this).css('border', '1px solid red').closest('div').find(
                                'span.error').html('Room type already selected.');
                            isStepFirstError = true;
                        } else {
                            jQuery(this).css('border', 'none').closest('div').find(
                                'span.error').html('');
                        }
                        preRoomType.push(roomType);
                    });

                    stepFirstscrollTo = false;
                    if (isStepFirstError) {
                        return false;
                    }

                    $(".first-step").hide();
                    $(".second-step").show();
                    $(".first-step").hide();
                    $(".thired-step").hide();
                    $(".forth-step").hide();

                });
                $(".step-2").click(function() {

                    jQuery('.helper-block,.invalid-feedback').each(function() {
                        jQuery(this).hide()
                    });

                    var isSecondFirstError = false;
                    var stepSecondscrollTo = false;
                    jQuery(
                            '.second-step input.requiredField, .second-step select.requiredField, .second-step textarea.requiredField'
                        )
                        .each(function() {
                            if (!jQuery(this).val()) {
                                isSecondFirstError = true;
                                jQuery(this).closest('div').find('.error').html(
                                    'This field is required.');
                                if (!stepSecondscrollTo) {
                                    stepSecondscrollTo = true;
                                    document.querySelector('body main #' + jQuery(this).attr(
                                        'id'))?.scrollIntoView({
                                        behavior: 'smooth'
                                    });
                                }
                            } else {
                                jQuery(this).closest('div').find('.error').html('');
                            }
                        });

                    if (isSecondFirstError) {
                        return false;
                    }
                    var settings = {
                        "url": "https://api.postalpincode.in/pincode/" + jQuery(
                            'input[name="pincode"]').val(),
                        "method": "GET",
                        "timeout": 0,
                    };
                    jQuery('input[name="pincode"]').closest('div').find('.error').addClass(
                        'checking').html('Pincode verifying...');
                    let apiResponase = $.ajax(settings).done(function(response) {
                        var isCodevalid = false;
                        var District = jQuery('select#city-dropdown option[value="' +
                            jQuery('select#city-dropdown').val() + '"]').html();
                        if (response[0].Status == "Success") {
                            response[0].PostOffice.forEach(function(val, key) {
                                if (val.District.toLowerCase() == District
                                    .toLowerCase() || val.Division.toLowerCase() ==
                                    District.toLowerCase() || val.District
                                    .toLowerCase() == District.toLowerCase() || val
                                    .Region.toLowerCase() == District
                                    .toLowerCase() || val
                                    .Block.toLowerCase() == District
                                    .toLowerCase()) {
                                    isCodevalid = true;
                                }
                            });
                        }

                        if (isCodevalid == false) {
                            isSecondFirstError = true;
                            jQuery('input[name="pincode"]').closest('div').find('.error')
                                .removeClass('checking').html(
                                    'Pincode not valid.');
                        } else {
                            jQuery('input[name="pincode"]').closest('div').find('.error')
                                .addClass('checking').html('Pincode verified');
                        }

                        stepSecondscrollTo = false;
                        if (isSecondFirstError) {
                            return false;
                        }
                        setTimeout(() => {
                            $(".first-step").hide();
                            $(".second-step").hide();
                            $(".thired-step").show();
                            $(".forth-step").hide();
                        }, 800);

                    });
                });
                $(".step-3").click(function() {

                    jQuery('.helper-block,.invalid-feedback').each(function() {
                        jQuery(this).hide()
                    });

                    var isThiredcondFirstError = false;
                    var stepThiredSecondscrollTo = false;
                    jQuery(
                            '.thired-step input.requiredField, .thired-step select.requiredField, .thired-step textarea.requiredField'
                        )
                        .each(function() {
                            if (!jQuery(this).val()) {
                                isThiredcondFirstError = true;
                                jQuery(this).closest('div').find('.error').html(
                                    'This field is required.');
                                if (!stepThiredSecondscrollTo) {
                                    stepThiredSecondscrollTo = true;
                                    document.querySelector('body main #' + jQuery(this).attr(
                                        'id'))?.scrollIntoView({
                                        behavior: 'smooth'
                                    });
                                }
                            } else {
                                jQuery(this).closest('div').find('.error').html('');
                            }
                        });
                    stepThiredSecondscrollTo = false;
                    if (isThiredcondFirstError) {
                        return false;
                    }

                    $(".first-step").hide();
                    $(".second-step").hide();
                    $(".thired-step").hide();
                    $(".forth-step").show();
                });
                $(".step-4").click(function() {
                    $(".first-step").hide();
                    $(".second-step").hide();
                    $(".thired-step").hide();
                    $(".forth-step").hide();
                });
            });

            $(function() {
                $(".prev-2").click(function() {
                    $(".first-step").show();
                    $(".second-step").hide();
                    $(".thired-step").hide();
                    $(".forth-step").hide();
                });
                $(".prev-3").click(function() {
                    $(".first-step").hide();
                    $(".second-step").show();
                    $(".thired-step").hide();
                    $(".forth-step").hide();
                });
                $(".prev-4").click(function() {
                    $(".first-step").hide();
                    $(".second-step").hide();
                    $(".thired-step").show();
                    $(".forth-step").hide();
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var i = $('.roomRow').length;
            $("#add-amenities").on("click", function() {
                i++;
                var html = '<div class="row roomRow">' +
                    '<div class="roomNumbers">Room : <span class="rNo"></span></div>'+
                    '<div class="removeRoom"><i class="mdi mdi-close-circle-outline" aria-hidden="true"></i></div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group ">' +
                    '<label for="address">Room Type</label>' +
                    '<select class="roomType form-control" name="hotel[' + i + '][roomtype]">' +
                    @if (!empty($roomtype))
                        @foreach ($roomtype as $key => $room)
                            '<option value="{{ $room->slug }}">{{ strlen($room->title) > 20 ? substr($room->title, 0, 20) . '...' : $room->title }}</option>' +
                        @endforeach
                    @endif
                '</select>' +
                '<span class="error"> </span>' +
                '</div>' +
                '</div>' +
                '<div class="col-sm-4">' +
                '<div class="form-group">' +
                '<label for="amenity">Select Amenities*</label>' +
                '<select class="js-example-basic-multiple" name="hotel[' + i +
                    '][amenity][]" multiple="multiple">' +
                    @if (!empty($Amenity))
                        @foreach ($Amenity as $key => $am)
                            '<option value="{{ $am->slug }}">{{ strlen($am->title) > 20 ? substr($am->title, 0, 20) . '...' : $am->title }}</option>' +
                        @endforeach
                    @endif
                '</select>' +
                '<span class="error"> </span>' +
                '</div>' +
                '</div>' +
                '<div class="col-sm-4">' +
                '<div class="form-group ">' +
                '<label for="bed_type">Bed Type*</label>' +
                '<select class="js-example-basic-multiple" name="hotel[' + i +
                    '][bed_type][]" multiple="multiple">' +
                    @if (!empty($bedtype))
                        @foreach ($bedtype as $key => $bedty)
                            '<option value="{{ $bedty->slug }}">{{ strlen($bedty->title) > 20 ? substr($bedty->title, 0, 20) . '...' : $bedty->title }}</option>' +
                        @endforeach
                    @endif
                '</select>' +
                '<span class="error"> </span>' +
                '</div>' +
                '</div>' +
                '<div class="col-sm-4">' +
                '<div class="form-group ">' +
                '<label for="price">No. of Rooms*</label>' +
                '<input type="number"  id="room" name="hotel[' + i +
                    '][no_room]" class="form-control" ><span class="error"> </span></div>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group ">' +
                    '<label for="price">Price*</label>' +
                    '<input type="number"  id="address" name="hotel[' + i +
                    '][rprice]" class="form-control" ><span class="error"> </span></div>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group ">' +
                    '<label for="price">Sale Price*</label>' +
                    '<input type="number"  id="address" name="hotel[' + i +
                    '][sprice]" class="form-control" ><span class="error"> </span></div>' +
                    '</div>' +
                    '<div class="col-sm-4">' +
                    '<div class="form-group ">' +
                    '<label for="price">Occupancy (Per room)*</label>' +
                    '<input type="number"  id="address" name="hotel[' + i +
                    '][occupancy]" class="form-control" ><span class="error"> </span></div>';
                '</div>' +
                '</div>';
                $(".append-amenty").append(html);
                $('.js-example-basic-multiple').select2();
                reOrderRoomNumber();
                checkRoomRemovable();
            });
        });
    </script>


@stop
