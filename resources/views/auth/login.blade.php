@extends('layouts.frontend')
@section('content')
<style>
    .invalid-error-msg{
    color: #dc3545;
    font-size: 13px;
    margin: 10px;
    }
    .custom-new {
    color: #fff;
    margin-left: 24px;
}
</style>
<section class="loginSec">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="loginFormBox w-100 bg-blue">
                    <img src="images/trawo.svg" height="30px" class="mx-auto d-block my-3 mt-md-4 mb-md-5">
                    <div class="invalid-error">
                        @if($errors->has('email'))
                                <div class="invalid-error-msg" >
                                    {{ $errors->first('email') }}
                                </div>
                        @endif
                    </div>
                    <div class="invalid-error">
                        @if($errors->has('password'))
                                <div class="invalid-error-msg" >
                                    {{ $errors->first('password') }}
                                </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <span class="inputIcon"><img src="images/email-icon.svg" alt="" width="20" height="20"></span>
                            {{-- <input class="form-control" type="email" placeholder="Email"> --}}
                            <input name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">

                        </div>
                        <div class="form-group">
                            <span class="inputIcon"><img src="images/password-icon.svg" alt="" width="20" height="20"></span>
                            {{-- <input class="form-control w-100" type="email" placeholder="Password"> --}}
                            <input name="password" type="password" class="form-control w-100 {{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}" id="myInput">
                            <a href="javascript:void(0)" style="position: absolute;top: 10px;right: 10px;" onclick="myFunction()"><i class="fa fa-eye"></i></a>

                        </div>
                        <div class="form-group row">
                            <div class="col-6">
                                {{-- <div class="cstmCheckbox">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div> --}}
                                <div class="custom-new">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('password.request') }}" class="forgotPassowrd"><u>{{ trans('global.forgot_password') }}</u></a>
                            </div>
                        </div>



                        {{-- <div class="form-group">
                            <div class="cstmCheckbox">
                                <input type="checkbox" id="termCheck">
                                <label for="termCheck">By sending this message, you confirm that you have read and
                                    agreed to our
                                    privacy-policy.</label>
                            </div>
                        </div> --}}
                        <button class="btn btn-light w-100" type="submit">Login</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<script>
function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

<!-- modal frogot password modal start -->
{{-- <div class="modal fade forget_password" id="forget_password">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-bs-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="text-center ">
                    <img src="img/logo.svg" alt="airtel_logo" width="150" height="34">
                </div>
                <h2 class="fs-5 fw-bold my-4">Link to reset password is sent to your email</h2>
                <p class="mb-3">Customer data privacy involves protecting and handling sensitive personal
                    information that an individual provides during </p>
                <div class="form-group mb-4 position-relative">
                    <input type="email" class="form-control pe-5" placeholder="Enter Email">
                    <span class="inputIcon">@</span>
                </div>
                <div class="form-group">
                    <button class="btn w-100 btn-secondary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    @if(\Session::has('message'))
                        <p class="alert alert-info">
                            {{ \Session::get('message') }}
                        </p>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <h1>{{ trans('panel.site_title') }}</h1>
                        <p class="text-muted">{{ trans('global.login') }}</p>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-4">
                            <div class="form-check checkbox">
                                <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                                <label class="form-check-label" for="remember" style="vertical-align: middle;">
                                    {{ trans('global.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ trans('global.login') }}
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                    {{ trans('global.forgot_password') }}
                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
