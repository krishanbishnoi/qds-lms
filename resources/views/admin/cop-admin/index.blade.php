@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        @can('corporate_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route("admin.cop-admin.create") }}"> {{ trans('global.add') }}  {{ trans('cruds.userManagement.corporateadmin') }}</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item">Corporate Admin</li>
        </ol>
      </nav>
    </div>

    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> Corporate Admin {{ trans('global.list') }}</h4>
            <div class="table-responsive">
                <table class="table table-striped" id="example">
                <thead>
                    <tr>
                        <th>
                            Sno.
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>

                        <th>
                            Wallet Amount
                        </th>
                        <th class="action">
                            {{ trans('cruds.action.title') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $i =1 @endphp
                    @foreach($copadmin as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            {{-- <td>

                            </td> --}}
                            <td>
                                {{ $i ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>

                            <td>
                                @php
                                $getmeta = \App\Models\CorporateMeta::where(['user_id' => $user->id])->where('name','=','walletamount')->pluck('value')->first();
                                @endphp
                                @if (!empty($getmeta))
                                        {{$getmeta}}
                                @else
                                0
                                @endif
                            </td>
                            <td>
                                <div class="actionBtnGroup">
                                    @can('user_show')
                                        {{-- <a class="btn btn-xs btn-primary" href="{{ route('admin.cop-admin.show', $user->id) }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a> --}}
                                    @endcan

                                    @can('user_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.cop-admin.edit', $user->id) }}">
                                            {{-- {{ trans('global.edit') }} --}}
                                            <i class="mdi mdi-eyedropper-variant"></i>
                                        </a>
                                    @endcan

                                    @can('user_delete')
                                        <form action="{{ route('admin.cop-admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></button>
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
