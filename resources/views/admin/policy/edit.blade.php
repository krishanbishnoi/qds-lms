@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.policy.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">Policy</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            {{-- <h4 class="card-title">Basic form elements</h4>
            <p class="card-description"> Basic form elements </p> --}}
            <form action="{{ route("admin.policy.update", [$policy->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$policy->id}}" >
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.company.fields.name') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('title', isset($policy) ? $policy->title : '') }}" required>
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
                <div class="form-group {{ $errors->has('hotel') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.hotel.title_singular') }}*</label>
                    @php
                        $hotelpolicy = $policy->hotel;
                        $explod = explode(',',$hotelpolicy);
                    @endphp
                    <select class="form-control select2" name="hotel[]" multiple="multiple">
                        @if(!empty($hotel))
                        @foreach ($hotel as $key => $hotel)
                        <option value="{{$hotel->id}}" {{ !empty($explod)  && in_array($hotel->id,$explod) ? 'selected' : ''}}>{{$hotel->title}}</option>
                        @endforeach
                        @endif
                    </select>
                    @if($errors->has('hotel'))
                        <em class="invalid-feedback">
                            {{ $errors->first('hotel') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.address_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="description">{{ trans('cruds.hotel.fields.description') }}*</label>
                    <textarea class="myCkeditor form-control" name="content" value="">{{ old('content', isset($policy) ? $policy->content : '') }}</textarea>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.description_helper') }}
                    </p>
                </div>

                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection
