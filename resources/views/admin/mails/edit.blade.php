@extends('layouts.admin')
@section('content')


<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.mails.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.mails.index') }}">Mail Template</a></li>
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
            <form action="{{ route("admin.mails.update", [$mail->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$mail->id}}" >
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="name">{{ trans('cruds.mailtemplate.fromname') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($mail) ? $mail->from_name : '') }}" required>
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
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('from_email') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="from_email">{{ trans('cruds.mailtemplate.fromemail') }}*</label>
                            <input type="text" id="name" name="from_email" class="form-control" value="{{ old('name', isset($mail) ? $mail->from_name : '') }}" required>
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
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('mail_cat') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="mail_cat">{{ trans('cruds.mailtemplate.mailcat') }}*</label>
                                <select class="form-control" name="mail_cat">
                                    <option value="" hidden>Select</option>
                                    <option value="hotel create" {{!empty($mail->mail_category) && ($mail->mail_category == 'hotel create') ? 'selected' : ''}}>Hotel Create</option>
                                </select>
                            </div>
                            @if($errors->has('mail_cat'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('mail_cat') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.name_helper') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="subject">{{ trans('cruds.mailtemplate.mailsubject') }}*</label>
                            <input type="text" id="name" name="subject" class="form-control" value="{{ old('name', isset($mail) ? $mail->subject : '') }}" required>
                            </div>
                            @if($errors->has('subject'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.name_helper') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('reply_mail') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="reply_mail">{{ trans('cruds.mailtemplate.repemail') }}*</label>
                            <input type="text" id="name" name="reply_mail" class="form-control" value="{{ old('name', isset($mail) ? $mail->reply_from_email : '') }}" required>
                            </div>
                            @if($errors->has('reply_mail'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('reply_mail') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.name_helper') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                            <label for="content">{{ trans('cruds.hotel.fields.content') }}*</label>
                            <textarea class="myCkeditor form-control"  name="content" value="" rows="12">{{ old('name', isset($mail) ? $mail->message_content : '') }}</textarea>
                            @if($errors->has('content'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.address_helper') }}
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
