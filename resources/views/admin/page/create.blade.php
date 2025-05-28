@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.page.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">Page</a></li>
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
            <form action="{{ route("admin.page.store") }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                @csrf

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>

                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">

                    <label for="name">{{ trans('cruds.pages.fields.description') }}*</label>
                    <textarea id="name" name="description" class="form-control" value="{{ old('description', isset($company) ? $company->name : '') }}" required></textarea>

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
                    <label for="address">{{ trans('cruds.hotel.fields.content') }}*</label>
                    <textarea class="myCkeditor form-control"  name="content" value="" ></textarea>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Banner upload</label>
                            <input type="file" name="banner" class="file-upload-default" accept="image/png, image/gif, image/jpeg">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                              </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Feature image upload</label>
                            <input type="file" name="feature" class="file-upload-default" accept="image/png, image/gif, image/jpeg">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                              </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <strong>Seo</strong>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Meta Title</label>
                            <input type="text" name="m_title" class="form-control" value="">
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Meta Keyword</label>
                            <input type="text" name="m_keyword" class="form-control"  value="">
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Meta Description</label>
                            <textarea type="text" name="m_description" class="form-control"  value=""> </textarea>
                        </div>
                        <p class="helper-block">
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
