@extends('admin.layouts.default')
@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>

                </ol>
            </nav>
        </div>

        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">VC Training Request List</h1>
                            </div>
                        </div>
                        <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">
                                <tr>
                                    <th>#</th>
                                    <th>Training Name</th>
                                    <th>User Name</th>
                                    <th>Status</th>
                                    <th>Requested At</th>
                                    <th>Update VC Status</th>
                                    <th>Status Updated By </th>
                                </tr>
                            </thead>

                            <tbody id="powerwidgets">
                                @forelse($requests as $index => $request)
                                    <tr>
                                        <td>{{ $requests->firstItem() + $index }}</td>
                                        <td>{{ $request->training->title ?? 'N/A' }}</td>
                                        <td>{{ $request->user->fullname ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $request->status == 'complated' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $request->requested_at ? \Carbon\Carbon::parse($request->requested_at)->format('d M Y, h:i A') : '-' }}
                                        </td>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <form action="{{ route('vc.request.update', $request->id) }}" method="POST"
                                                style="display:inline-block;margin: 0; margin-top: 0 !important;">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()"
                                                    class="form-select form-select-sm">
                                                    <option value="">Mark Status</option>
                                                    <option value="complated"
                                                        {{ $request->status == 'complated' ? 'selected' : '' }}>Complated
                                                    </option>
                                                    <option value="rejected"
                                                        {{ $request->status == 'rejected' ? 'selected' : '' }}>Reject
                                                    </option>
                                                </select>
                                            </form>

                                        </td>
                                        <td>{{ $request->statususer->fullname ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No VC training requests found.</td>
                                    </tr>
                                @endforelse
                            </tbody>


                        </table>
                        <div class="box-footer clearfix">
                            <!-- <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $requests])</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>


@stop
