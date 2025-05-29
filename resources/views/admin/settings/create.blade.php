@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h4 class="card-title">Site Settings</h4>
        {{-- <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.roomtype.index') }}">List  {{ trans('cruds.allhotel.fields.roomtype') }}</a> --}}
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item">Settings</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            {{-- <h4 class="card-title">Basic form elements</h4>
            <p class="card-description"> Basic form elements </p> --}}
            <form id="settingsForm" action="{{ route("admin.settings.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <strong>Site Information</strong>
                    <hr>
                </div>
                <input type="hidden" name="group" value="general">

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} mt-5">
                    <div class="grp-f">
                    <label for="title">{{ trans('cruds.settings.fields.sitetitle') }}*</label>
                    <input type="text" id="name" name="title" class="form-control" value="{{ old('title', isset($Settings) ? $Settings['title'] : '') }}" required>
                    <span class="error"></span>
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
                    <label for="description">{{ trans('cruds.pages.fields.description') }}*</label>
                    <textarea id="name" name="description" class="form-control" value="" required>{{ old('description', isset($Settings) ? $Settings['description'] : '') }}</textarea>
                    <span class="error"></span>
                </div>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>


                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="content">{{ trans('cruds.hotel.fields.content') }}*</label>
                    <textarea required class="myCkeditor form-control"  name="content" value="">{{ old('content', isset($Settings) ? $Settings['content'] : '') }}</textarea>
                    @if($errors->has('address'))
                        <em class="invalid-feedback">
                            {{ $errors->first('content') }}
                        </em>
                    @endif
                    <span class="error"></span>
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.address_helper') }}
                    </p>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <img src="{{url('setting').'/'.$Settings['favicon']}}" alt="" style="height:50px">
                    </div>
                    <div class="col-md-6">
                        <img src="{{url('setting').'/'.$Settings['logo']}}" alt="" style="height:50px">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('favicon') ? 'has-error' : '' }} ">
                            <div class="grp-f">
                            <label for="favicon">{{ trans('cruds.settings.fields.favicon') }}*</label>
                            <input type="file" accept="image/png, image/jpeg, image/jpg" id="title" name="favicon" class="form-control"  value="" {{!$Settings['favicon']?'required':''}} />
                                <span class="error"></span>
                        </div>
                        @if($errors->has('favicon'))
                        <em class="invalid-feedback">
                            {{ $errors->first('favicon') }}
                        </em>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }} ">
                            <div class="grp-f">
                            <label for="logo">{{ trans('cruds.settings.fields.logo') }}*</label>
                            <input type="file" accept="image/png, image/jpeg, image/jpg" id="logo" name="logo" class="form-control"  value="" {{!$Settings['logo']?'required':''}}>
                            <span class="error"></span>
                        </div>
                        @if($errors->has('logo'))
                            <em class="invalid-feedback">
                                {{ $errors->first('logo') }}
                            </em>
                        @endif
                        </div>
                    </div>

                </div>

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} ">
                    <label for="title">{{ trans('cruds.settings.fields.dateformate') }}*</label>
                    <input type="date" id="name" name="date" class="form-control" placeholder="DD/MM/YYYY" value="{{ old('content', isset($Settings) ? $Settings['date'] : '') }}" required>
                    @error('date')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <strong>Contact Settings</strong>
                    <hr>
                </div>

                <div class="row  mt-5">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} ">
                            <label for="phone">{{ trans('cruds.settings.fields.phone') }}*</label>
                            {{-- <input type="number" id="phone" name="phone" class="form-control"  value="{{ old('content', isset($Settings) ? $Settings['phone'] : '') }}" required> --}}
                            <input type="tel" id="mobile_code" class="form-control numberInput" placeholder="Enter Mobile Number" name="phone" onkeyup="checkPass(); return false;" minlength="10" maxlength="10" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);this.value = this.value.replace(/[^0-9+()]/g, '');" pattern=".{8,10}" value="{{ old('content', isset($Settings) ? $Settings['phone'] : '') }}">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} ">
                            <label for="email">{{ trans('cruds.settings.fields.email') }}*</label>
                            <input type="email" id="name" name="email" class="form-control"  value="{{ old('content', isset($Settings) ? $Settings['email'] : '') }}" required>
                            @if($errors->has('email'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </em>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} ">
                            <label for="address">{{ trans('cruds.settings.fields.address') }}*</label>
                            <textarea id="address" name="address" class="form-control"  value="" required>{{ old('name', isset($Settings) ? $Settings['address'] : '') }}</textarea>
                            @if($errors->has('address'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </em>
                            @endif
                            <span class="error"></span>
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.name_helper') }}
                            </p>
                        </div>
                    </div>

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
@section('scripts')
<script>
    $("#mobile_code").intlTelInput({
        initialCountry: "in",
        separateDialCode: true,
        // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
</script>
@stop

