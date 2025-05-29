@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.tags.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
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
            <form action="{{ route("admin.tags.store") }}" method="POST" enctype="multipart/form-data">
                @csrf


                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                <label for="name">Title*</label>
                                <input type="text" id="name" name="title" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                                </div>
                                @if($errors->has('name'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('title') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('from_email') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                <label for="from_email">Disount (in %)*</label>
                                <input type="text" id="name" name="discount" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                                </div>
                                @if($errors->has('from_email'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('from_email') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('from_email') ? 'has-error' : '' }}">

                                <label for="from_email">Select Corporate User</label>
                                <select class="form-control select2" name="user[]" multiple>
                                    @foreach ($copadmin as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('from_email'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('from_email') }}
                                    </em>
                                @endif
                                <p class="helper-block">
                                    {{ trans('cruds.company.fields.name_helper') }}
                                </p>
                            </div>
                        </div>

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

