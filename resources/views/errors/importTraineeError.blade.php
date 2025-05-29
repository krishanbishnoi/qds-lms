@extends('admin.layouts.default')
@section('content')
    <div class="content-wrapper">

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h2 class="mb-0">Error(s) occurred during import file:</h2>
                        </div>
                        <div class="">
                            @if (count($errors) > 0)
                                <ul class="list-group">
                                    @foreach ($errors as $error)
                                        <li class="list-group-item list-group-item-danger"><strong>{{ $error }}</strong></li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-success">
                                    No errors to display.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop
