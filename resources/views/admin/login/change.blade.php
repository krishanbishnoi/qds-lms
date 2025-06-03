@extends('layouts.admin-login')
@section('content')
    <div class="content-wrapper d-flex align-items-center auth ms-0">
        <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left p-5">
                    <div class="brand-logo">
                        <img src="/img/trawo3.svg">
                    </div>
                    <h4>Reset Password</h4>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    {{-- <h6 class="font-weight-light">Sign in to continue.</h6> --}}
                    {{ Form::open(['role' => 'form', 'url' => 'admin/change-password-action', 'class' => 'pt-3']) }}
                    <div class="form-group">
                        {!! Form::input('email', 'email', '', ['class' => 'form-control form-control-lg', 'placeholder' => 'Username']) !!}
                    </div>
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        {!! Form::input('password', 'password', '', [
                            'class' => 'form-control form-control-lg',
                            'placeholder' => 'password',
                        ]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::input('password', 'password_confirmation', '', [
                            'class' => 'form-control form-control-lg',
                            'placeholder' => 'confirm password',
                        ]) !!}
                    </div>

                    <div class="mt-3">
                        <button type="submit"
                            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                            name="admin-login">Reset Password</button>
                    </div>

                    {{-- <div class="mb-2">
              <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                <i class="mdi mdi-facebook me-2"></i>Connect using facebook </button>
            </div> --}}
                    {{-- <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="register.html" class="text-primary">Create</a>
            </div> --}}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
