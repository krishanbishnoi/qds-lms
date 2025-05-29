@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.policy.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">Policy</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            {{-- <h4 class="card-title">Basic form elements</h4>
            <p class="card-description"> Basic form elements </p> --}}
            <form action="{{ route("admin.policy.store") }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                    </div>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('hotel') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.hotel.title_singular') }}*</label>
                    <select class="form-control select2" name="hotel[]" multiple="multiple">
                        @if(!empty($hotel))
                        @foreach ($hotel as $key => $hotel)
                        <option value="{{$hotel->id}}">{{$hotel->title}}</option>
                        @endforeach
                        @endif
                    </select>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="address">{{ trans('cruds.hotel.fields.description') }}*</label>
                    <textarea class="myCkeditor form-control"  name="content" value=""></textarea>
                    @if($errors->has('address'))
                        <em class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.address_helper') }}
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
