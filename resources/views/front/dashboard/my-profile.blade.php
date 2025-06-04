@extends('front.layouts.trainee-default')
@section('content')
    <div class="dashboard-main">
        <div class="containter-fluid">
            <div class="d-flex flex-wrap justify-content-end">
                {{-- Include Sidebar --}}
                @include('front.layouts.trainee-sidebar')
                <div class="dashboard_content">
                    <div class="dashboard_head mb-md-5 d-lg-flex justify-content-between">
                        <div class="">
                            <h1 class="fs-2">My <span>Profile</span></h1>
                        </div>
                        {{-- for ntifications file --}}
                        @include('front.layouts.notification')
                    </div>
                    <div class="box mb-4">
                        <form action="{{ route('trainee.profile.save') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class=" profile-section d-md-flex align-items-end justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        @if (!empty(Auth::user()->image))
                                            <i><img src="{{ Auth::user()->image }}" alt="img" width="110"
                                                    style="border-radius:55px"></i>
                                        @else
                                            @php
                                                $first_letter = strtoupper(substr($userProfile->fullname, 0, 1));
                                                $space_index = strpos($userProfile->fullname, ' ');
                                                $second_letter = strtoupper(
                                                    substr($userProfile->fullname, $space_index + 1, 1),
                                                );
                                            @endphp
                                            <span class="employeeFirstLetter">
                                                {{ $first_letter . '' . $second_letter }}</span>
                                        @endif
                                        <div class="position-relative">
                                            <label for="editFile" class="edit-icon">
                                                <img src="{{ asset('front/img/profile-edit.svg') }}" alt="edit-icon"
                                                    width="25">
                                            </label>
                                            <input type="file" id="editFile" name="profile_image"
                                                class="position-absolute d-none">
                                            <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                        </div>
                                    </div>
                                    <div class="profile-name ms-3">
                                        <h2 class="fs-3 text-dark fw-semibold">Profile</h2>
                                        <p class="lightGreyTxt">Update your profile photo here</p>
                                    </div>
                                </div>

                                <div class="btnGroup d-flex gap-3 mt-4 mt-md-0">
                                    <a href="{{ url()->previous() }}">
                                        <button type="button" class="btn btn-light smallBtn">Back</button>
                                    </a>
                                    <button type="submit" class="btn btn-secondary smallBtn">Save</button>
                                </div>

                            </div>
                        </form>
                        <hr class="hrline">
                        <form class="profile-info">
                            <h2 class="fs-5 fw-semibold blue-text mb-4">Personal Information</h2>
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class=" d-flex align-items-center">
                                        <label>Username</label>
                                        <input type="text" class="form-control" readonly
                                            value="{{ ucwords($userProfile->first_name . ' ' . $userProfile->last_name) }}"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class=" d-flex align-items-center">
                                        <label>Emp ID</label>
                                        <input type="text" class="form-control" readonly
                                            value="{{ $userProfile->olms_id }}" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <hr class="hrline">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class=" d-flex align-items-center">
                                        <label>Email ID</label>
                                        <input type="email" class="form-control" placeholder="connor.spencer@qdegrees.com"
                                            readonly value="{{ $userProfile->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class=" d-flex align-items-center">
                                        <label>Position</label>
                                        <input type="text" class="form-control" placeholder="" readonly
                                            value="{{ 'Trainee' }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="hrline">

                            <div class="d-flex align-items-center">
                                <label>Manager</label>
                                <input type="text" class="form-control" placeholder="" readonly
                                    value="{{ $userProfile->parentManager->fullname }}">
                            </div>

                            {{-- <hr class="hrline">

                            <div class=" d-flex align-items-center">
                                <label>Description</label>
                                <textarea name="" id="" rows="4" readonly
                                    placeholder="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book."></textarea>
                            </div> --}}
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
