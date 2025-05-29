@extends('layouts.admin')
@section('content')


<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.page.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">Page</a></li>
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
            <form action="{{ route("admin.page.update", [$page->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$page->id}}" >
                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('title', isset($page) ? $page->title : '') }}" required>
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
                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <div class="grp-f">
                    <label for="name">{{ trans('cruds.pages.fields.description') }}*</label>
                    <textarea id="name" name="description" class="form-control" value="" required>{{ old('description', isset($page) ? $page->description : '') }}</textarea>
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
                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="description">{{ trans('cruds.hotel.fields.content') }}*</label>
                    <textarea class="myCkeditor form-control" name="content" value="">{{ old('content', isset($page) ? $page->content : '') }}</textarea>

                </div>

               {{-- <div class="row">
                    <div class="col-sm-6">
                        <img src="{{url('hotel\banner').'/'.$hotel['primary_image']}}" alt="" style="height:50px">
                    </div>
                    <div class="col-sm-6">
                        <img src="{{url('hotel\banner').'/'.$hotel['primary_image']}}" alt="" style="height:50px">
                    </div>
            </div>--}}

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group {{ $errors->has('banner') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="address">{{ trans('cruds.hotel.fields.banner_image') }}</label>
                            <input type="file" name="banner" class="form-control">
                            </div>
                            @if($errors->has('address'))
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
                        <div class="form-group {{ $errors->has('feature') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="address">{{ trans('cruds.hotel.fields.feature_image') }}</label>
                            <input type="file" name="feature" class="form-control">
                            </div>
                            @if($errors->has('address'))
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

                <div class="form-group mt-2">
                    <strong>SEO</strong>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="grp-f">
                        <label>Meta Title</label>
                        <input type="text" name="m_title" class="form-control" value="{{ old('description', isset($page) ? $page->meta_title : '') }}">
                        </div>
                        <p class="helper-block">
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <div class="grp-f">
                        <label>Meta Keyword</label>
                        <input type="text" name="m_keyword" class="form-control"  value="{{ old('description', isset($page) ? $page->meta_keyword : '') }}">
                        </div>
                        <p class="helper-block">
                        </p>
                    </div>
                    <div class="col-sm-12">
                        <div class="grp-f">
                        <label>Meta Description</label>
                        <textarea type="text" name="m_description" class="form-control"  value="{{ old('description', isset($page) ? $page->meta_description : '') }}"> </textarea>
                        </div>
                        <p class="helper-block">
                        </p>
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
