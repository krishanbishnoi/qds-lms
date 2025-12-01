@extends('admin.layouts.default')
@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h1>{{ $sectionName }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>
                </ol>
            </nav>
        </div>

        {{-- SEARCH PANEL --}}
        <div class="box search-panel collapsed-box">
            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}

                <div class="col-md-2 col-sm-2">
                    <div class="form-group">
                        {!! Form::label('name', 'Agency Name', ['class' => 'mws-form-label']) !!}
                        {{ Form::text('name', $searchVariable['name'] ?? '', ['class' => 'form-control', 'placeholder' => 'Search Agency']) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 paddingtop">
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href="{{ route("$modelName.index") }}" class="btn btn-primary"><i class="fa fa-refresh"></i>
                            Clear Search</a>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>

        {{-- LISTING --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">{{ $sectionName }}'s List</h1>

                                <a href="{{ route("$modelName.add") }}" class="btn btn-success btn-small pull-right mb-2">
                                    Add New {{ $sectionNameSingular }}
                                </a>
                            </div>
                        </div>

                        <table class="table table-hover table table-bordered mt-2">
                            <thead class="theadLight">
                                <tr>
                                    <th>SN</th>

                                    {{-- Sort by Agency Name --}}
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            'Agency Name',
                                            [
                                                'sortBy' => 'name',
                                                'order' => $sortBy == 'name' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'name' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'name' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Code</th>
                                    <th>Region</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @php $sn = ($results->currentPage() - 1) * $results->perPage() + 1; @endphp

                                    @foreach ($results as $record)
                                        <tr class="items-inner">
                                            <td>{{ $sn++ }}</td>
                                            <td>{{ $record->name }}</td>
                                            <td>{{ $record->email }}</td>
                                            <td>{{ $record->mobile_no }}</td>
                                            <td>{{ $record->agency_code }}</td>
                                            <td>{{ $record->region->region ?? '-' }}</td>
                                            <td>{{ $record->state->name ?? '-' }}</td>
                                            <td>{{ $record->city->city ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $record->status ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$record->status] ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="action-td">
                                                <a href="{{ route("$modelName.edit", $record->id) }}"
                                                    class="btn btn-primary" title="Edit">
                                                    <span class="fas fa-edit"></span>
                                                </a>
                                                <a href="{{ route("$modelName.delete", $record->id) }}"
                                                    class="btn btn-danger" title="Delete">
                                                    <span class="fas fa-trash"></span>
                                                </a>
                                                @php
                                                    $isActive = $record->status;
                                                @endphp

                                                <a href="{{ route("$modelName.status", ['id' => $record->id, 'status' => $isActive ? 0 : 1]) }}"
                                                    class="btn {{ $isActive ? 'btn-warning' : 'btn-success' }}" title="Active/Inactive" >
                                                    <span class="fas {{ $isActive ? 'fa-times' : 'fa-check' }}"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="alignCenterClass" colspan="11">No Record Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="box-footer clearfix">
                            <div class="col-md-12 col-sm-12 text-right">
                                @include('pagination.default', ['paginator' => $results])
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .paddingtop {
            padding-top: 20px;
        }
    </style>

@stop
