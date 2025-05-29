@extends('layouts.admin-login')
@section('content')
<div class="content-wrapper d-flex align-items-center auth">
    <div class="row flex-grow">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left p-5">
          <div class="brand-logo">
          <img  height="100%" width="100%"  src="{{ asset('admin/images/logo.PNG') }}"
                                alt="logo" />
          </div>
          <h4>Hello! let's get started</h4>
          @if (session()->has('success'))
          <div class="alert alert-success">
              {{ session()->get('success') }}
          </div>
            @endif
            @error('email')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
            @enderror
            @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
            @enderror
           <h6 class="font-weight-light">Sign in to continue.</h6>
            {{ Form::open(['role' => 'form','url' => 'admin/login-action','class' => 'pt-3']) }}
            <div class="form-group">
              
              {!! Form::input('email', 'email','', ['class' => 'form-control form-control-lg','placeholder' => 'Username']) !!}
            </div>
            <div class="form-group">
                {!! Form::input('password','password','', ['class' => 'form-control form-control-lg','placeholder' => 'Password']) !!}
           
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" name="admin-login">Login</button>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
              <div class="form-check">
                <label class="form-check-label text-muted">
                  <input type="checkbox" class="form-check-input"> Keep me signed in </label>
              </div>
              <a href="{{route('admin.forgot')}}" class="auth-link text-black">Forgot password?</a>
            </div>
            
            {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
@endsection
