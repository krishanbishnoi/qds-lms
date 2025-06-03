@extends('admin.layouts.login_layout')
@section('content')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth  ms-0">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img height="100%" width="100%" src="{{ asset('lms-img/qdegrees-logo.svg') }}"
                                    alt="logo" />
                            </div>
                            <h4>Forget Password</h4>
                            {{-- <h6 class="font-weight-light">Sign in to continue.</h6> --}}
                            {{ Form::open(['role' => 'form', 'url' => 'admin/send_password', 'class' => 'forms-sample']) }}

                            <div class="form-group">
                                {{-- <label for="exampleInputUsername1">Email</label> --}}
                                <input type="text" class="form-control" name="email" id="exampleInputUsername1"
                                    placeholder="Email">
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('email'); ?>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                            <a href="{{ URL::to('/admin/login') }}" class="btn btn-light">Cancel</a>

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>


    @stop
