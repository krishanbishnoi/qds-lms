@extends('admin.layouts.login_layout')
@section('content')

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth ms-0">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img height="100%" width="100%" src="{{ asset('lms-img/qdegrees-logo.svg') }}"
                                    alt="logo" />
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            {{ Form::open(['role' => 'form', 'method' => 'post', 'url' => 'admin/login', 'enctype' => 'multipart/form-data']) }}

                            <div class="form-group">
                                <input type="text" name="email" class="form-control form-control-lg"
                                    placeholder="Email">
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('email'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-lg"
                                        id="login_password" placeholder="Password">
                                    <span class="input-group-text eye_icon" data-id="login_password"
                                        style="cursor:pointer;">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                </div>
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('password'); ?>
                                </div>
                            </div>
                            <div class="mt-3">
                                <input type="submit"
                                    class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                                    name="login">
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        {{-- <input type="checkbox" class="form-check-input"> Keep me signed in </label> --}}
                                </div>
                                <a href="{{ URL::to('admin/forget_password') }}" class="auth-link text-black">Forgot
                                    password?</a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>


    <script>
        document.querySelectorAll('.eye_icon').forEach(function(toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const input = document.getElementById(id);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>




@stop
