@extends('layouts.admin')
@section('content')
@can('company_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route("admin.cop-admin.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.userManagement.wallet') }}
            </a>
        </div>
    </div>
@endcan
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
</div>
@endif
<div class="card">
    <div class="card-header heading-title">
        {{ trans('cruds.userManagement.wallet') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="tblM w-100">
        <div class="table-responsive w-100 mainTbl mainTbl2">
            <table class=" table mb-0">
                <thead>
                    <tr>
                        {{-- <th width="10">

                        </th> --}}
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>

                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th class="action">
                            {{ trans('cruds.action.title') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wallet as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            {{-- <td>

                            </td> --}}
                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
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
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan

                                    @can('user_delete')
                                        <form action="{{ route('admin.cop-admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>


    </div>
</div>
@endsection
@section('scripts')

@endsection
