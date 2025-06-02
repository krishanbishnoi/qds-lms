@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        @can('role_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route("admin.roomtype.create") }}">  {{ trans('global.add') }} {{ trans('cruds.allhotel.fields.roomtype') }}</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Room Type</li>
        </ol>
      </nav>
    </div>

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">   {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}</h4>
            <div class="table-responsive">
                <table class="table table-striped" id="example">
                    <thead>
                        <tr>
                            <th>
                                Sno.
                            </th>
                            <th>
                                {{ trans('cruds.role.fields.permissions') }}
                            </th>
                            <th class="action">
                                {{ trans('cruds.action.title') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i =1 @endphp
                        @foreach($roomtype as $key => $roomtype)
                        <tr data-entry-id="{{ $roomtype->id }}">
                            {{-- <td>

                            </td> --}}
                            <td>
                                {{ $roomtype->id ?? '' }}
                            </td>
                            <td>
                                {{ $roomtype->title ?? '' }}
                            </td>


                            <td>
                                <div class="actionBtnGroup">
                                    @can('roomtype_show')
                                        {{-- <a class="btn btn-xs btn-primary" href="{{ route('admin.roomtype.show', $roomtype->id) }}">
                                            {{ trans('global.view') }}
                                        </a> --}}
                                    @endcan

                                    @can('roomtype_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.roomtype.edit', $roomtype->id) }}">
                                            {{-- {{ trans('global.edit') }} --}}<i class="mdi mdi-eyedropper-variant"></i>
                                        </a>
                                    @endcan

                                    @can('roomtype_delete')
                                        <form action="{{ route('admin.roomtype.destroy', $roomtype->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-xs btn-danger" ><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    @endcan
                                </div>

                            </td>

                        </tr>
                        @php $i++ @endphp
                    @endforeach

                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection
@section('scripts')
<script>
    new DataTable('#example', {
        autoWidth: false,
        aLengthMenu: [
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ],
        columnDefs: [
            {
                targets: ['3,4,5'],
                className: 'mdc-data-table__cell',
            },
        ],
    });
      </script>
@stop
