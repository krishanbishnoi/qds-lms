@extends('admin.layouts.login_layout')
@section('content')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img  height="100%" width="100%"  src="{{ asset('lms-img/qdegrees-logo.svg') }}"
                                alt="logo" />
                            </div>
                            <h4>Reset Password</h4>
                            {{-- <h6 class="font-weight-light">Sign in to continue.</h6> --}}
                            {{ Form::open(['role' => 'form','url' => 'admin/save_password' ,'id'=>'change_password_form']) }}
                              {{ Form::hidden('validate_string',$validate_string, []) }}

                                <div class="form-group">
                                    {{-- <label for="exampleInputUsername1">New Password</label> --}}
                                    {{ Form::password('new_password',['class' => 'form-control','placeholder'=>'New Password','id'=>'new_password']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('new_password'); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{-- <label for="exampleInputUsername1">Confirm Password</label> --}}
                                    {{ Form::password('new_password_confirmation', ['class' => 'form-control','placeholder'=>'Confirm Password','id'=>'new_password_confirmation']) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('new_password_confirmation'); ?>
                                        </div>
                                </div>


                   <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                   <a  href="{{ URL::to('/admin')}}"  class="btn btn-light">Cancel</a>

                    {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>


@stop
