@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        @can('corporate_create')
        <a class="btn btn-gradient-primary btn-fw" href="{{ route("admin.contacts.index") }}"> Back</a>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route("admin.contacts.index") }}">Contact</a></li>
          <li class="breadcrumb-item active" aria-current="page">Show</li>
        </ol>
      </nav>
    </div>
    @if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif
    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> Contact Show</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>

                        <tr>
                            <th>
                                {{ trans('cruds.contact.name') }}
                            </th>
                            <td>
                                {{ $contact->name ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.contact.email') }}
                            </th>
                            <td>
                                {{ $contact->email ?? ''}}
                            </td>
                        </tr>



                        <tr>
                            <th>
                                {{ trans('cruds.contact.number') }}
                            </th>
                            <td>
                                {{ $contact->name ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.contact.company_name') }}
                            </th>
                            <td>
                                {{ $contact->company_name ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.contact.url') }}
                            </th>
                            <td>
                                {{ $contact->company_url ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                {{ trans('cruds.contact.msg') }}
                            </th>
                            <td>
                                {!! $contact->msg  !!}
                            </td>
                        </tr>


                    </tbody>
                </table>
                {{-- <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a> --}}
            </div>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection

