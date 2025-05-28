@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.bedtype.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route("admin.bedtype.index") }}">{{ trans('cruds.allhotel.fields.bedtype') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <form action="{{ route("admin.bedtype.update", [$bedtype->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$bedtype->id}}" >
                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('name', isset($bedtype) ? $bedtype->title : '') }}" required>
                    </div>
                    @if($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>


                {{--<div class="form-group {{ $errors->has('roomtype') ? 'has-error' : '' }} hideifchecked">
                    <label for="name">{{ trans('cruds.hotel.fields.room_type') }}*</label>
                    @php
                    $type = $bedtype->room_type;
                    $explod = explode(',',$type);
                    @endphp
                    <select class="js-example-basic-multiple" name="roomtype[]" multiple="multiple">
                        @if(!empty($roomtype))
                        @foreach ($roomtype as $key => $room)
                        <option value="{{$room->id}}" {{ !empty($explod)  && in_array($room->id,$explod) ? 'selected' : ''}}>{{$room->title}}</option>
                        @endforeach
                        @endif
                    </select>
                    @if($errors->has('roomtype'))
                        <em class="invalid-feedback">
                            {{ $errors->first('roomtype') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>--}}



                <div>
                    <input class="btn btn-gradient-primary me-2" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection


