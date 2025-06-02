@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.roomtype.index') }}">List  {{ trans('cruds.allhotel.fields.roomtype') }}</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roomtype.index') }}">Romm Type</a></li>
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
            <form action="{{ route("admin.roomtype.update", [$roomtype->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$roomtype->id}}" >
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($roomtype) ? $roomtype->title : '') }}" required>
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

