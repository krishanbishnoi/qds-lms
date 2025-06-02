@extends('admin.layouts.default')
@section('content')
    <?php
    $userInfo = Auth::user();
    $full_name = isset($userInfo->full_name) ? $userInfo->full_name : '';
    $username = isset($userInfo->username) ? $userInfo->username : '';
    $email = isset($userInfo->email) ? $userInfo->email : '';
    ?>
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="text-center">Change Password </h1>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['role' => 'form', 'url' => 'admin/changed-password', 'class' => 'mws-form', 'id' => 'change_password_form', 'files' => 'true']) }}
                        <!-- Old Password -->
                        <div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
                            {!! Html::decode(
                                Form::label('old_password', 'Old Password<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                            ) !!}
                            <div class="input-group">
                                {{ Form::password('old_password', ['class' => 'form-control', 'id' => 'old_password']) }}
                                <span class="input-group-text eye_icon" data-id="old_password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <div class="error-message help-inline">{{ $errors->first('old_password') }}</div>
                        </div>

                        <!-- New Password -->
                        <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                            {!! Html::decode(
                                Form::label('new_password', 'New Password<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                            ) !!}
                            <div class="input-group">
                                {{ Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password']) }}
                                <span class="input-group-text eye_icon" data-id="new_password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <div class="error-message help-inline">{{ $errors->first('new_password') }}</div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                            {!! Html::decode(
                                Form::label('confirm_password', 'Confirm Password<span class="requireRed"> * </span>', [
                                    'class' => 'mws-form-label',
                                ]),
                            ) !!}
                            <div class="input-group">
                                {{ Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password']) }}
                                <span class="input-group-text eye_icon" data-id="confirm_password">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            <div class="error-message help-inline">{{ $errors->first('confirm_password') }}</div>
                        </div>


                        <div class="mws-button-row">
                            <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                            <a href="{{ URL::to('admin/change-password') }}" class="btn btn-primary"><i
                                    class=\"icon-refresh\"></i> {{ trans('Reset') }}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <script>
                    $(".eye_icon").click(function() {
                        const id = $(this).data("id");
                        const $icon = $(this).find("i");
                        const $input = $("#" + id);

                        if ($input.attr("type") === "password") {
                            $input.attr("type", "text");
                            $icon.removeClass("bi-eye-slash").addClass("bi-eye");
                        } else {
                            $input.attr("type", "password");
                            $icon.removeClass("bi-eye").addClass("bi-eye-slash");
                        }
                    });
                </script>

            </div>
            <div>
                <div>
                @stop
