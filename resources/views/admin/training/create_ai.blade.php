@extends('layouts.admin')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.users.index') }}">Back</a>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.training.index') }}">Training</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.api.result') }}" method="POST" enctype="multipart/form-data"
                            class="forms-sample">
                            @csrf
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Enter Your Training Subject:*</label>
                                <input type="text" id="name" name="text" class="form-control"
                                    value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                            </div>
                            <div>
                                <input class="btn btn-gradient-primary me-2" type="submit"
                                    value="Create Training">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
