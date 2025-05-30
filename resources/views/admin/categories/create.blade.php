@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header heading-title">
        {{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.categories.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.category.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.category.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                <label for="icon">{{ trans('cruds.category.fields.icon') }}</label>
                <input type="text" id="icon" name="icon" class="form-control" value="{{ old('icon', isset($category) ? $category->icon : '') }}">
                @if($errors->has('icon'))
                    <em class="invalid-feedback">
                        {{ $errors->first('icon') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.category.fields.icon_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection
