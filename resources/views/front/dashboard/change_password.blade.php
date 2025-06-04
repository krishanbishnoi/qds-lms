@extends('front.layouts.trainee-default')
@section('content')
    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{-- Include Sidebar --}}
                @include('front.layouts.trainee-sidebar')
                <div class="dashboard_content">
                    <div class="dashboard_head mb-md-5">
                        {{-- <h1 class="fs-2"><span>Update</span> your password below. </h1> --}}
                    </div>
                    @if (session('message'))
                        <div class="alert alert-success">
                            <b>{{ session('message') }}</b>
                        </div>
                    @endif
                    <div class="box mb-4">
                        <div class=" profile-section d-md-flex align-items-end justify-content-between">
                            <div class="d-flex align-items-center">

                                <div class="profile-name ms-3">
                                    <h2 class="fs-3 text-dark fw-semibold">Change Password</h2>
                                    <p class="lightGreyTxt">Update your password below.</p>
                                </div>
                            </div>
                            <div class="btnGroup d-flex gap-3 mt-4 mt-md-0">
                                <a href="{{ url()->previous() }}">
                                    <button type="button" class="btn btn-light smallBtn">Back</button>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <form class="profile-info" action="{{ url('/changed-password') }}" method="POST">
                            @csrf
                            <h2 class="fs-5 fw-semibold blue-text mb-4">Password Information</h2>

                            <div class="mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="old_password" id="old_password"
                                        placeholder="Enter old password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="old_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('old_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="new_password" id="new_password"
                                        placeholder="Enter new password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="new_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Re-enter your new password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="confirm_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('confirm_password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary">Save</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
                target.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
@stop
