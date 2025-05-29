@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h4 class="card-title">Social Settings</h4>
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
                    <strong>Social Information</strong>
                    <hr>
                </div>
                <input type="hidden" name="group" value="social">

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} mt-5">
                    <label for="title">Facebook Link*</label>
                    <input type="text" id="name" name="facebook" class="form-control" value="{{ old('facebook', isset($Settings) ? $Settings['facebook'] : '') }}" required>
                    <span class="error"></span>
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
                    <label for="description">Instagram Link*</label>
                    <input type="text" id="name" name="instagram" class="form-control" value="{{ old('instagram', isset($Settings) ? $Settings['instagram'] : '') }}" required>
                    <span class="error"></span>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>

                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">Linkdin Link*</label>
                    <input type="text" id="name" name="linkdin" class="form-control" value="{{ old('linkdin', isset($Settings) ? $Settings['linkdin'] : '') }}" required>
                    <span class="error"></span>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
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
@section('scripts')
<script>
    $("#mobile_code").intlTelInput({
        initialCountry: "in",
        separateDialCode: true,
        // utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
    });
</script>
@stop

