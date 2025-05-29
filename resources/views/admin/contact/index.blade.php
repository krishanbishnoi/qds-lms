@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        @can('company_create')
        {{-- <a class="btn btn-gradient-primary btn-fw" href="{{ route("admin.page.create") }}">{{ trans('global.add') }} {{ trans('cruds.contact.title') }}</a> --}}
        <h4 class="card-title"> {{ trans('cruds.contact.title') }} {{ trans('global.list') }}</h4>
        @endcan
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
          <li class="breadcrumb-item">Contacts</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"> {{ trans('cruds.contact.title') }} {{ trans('global.list') }}</h4>

            <table class="table table-striped" id="example">
              <thead>
                <tr>
                    <th>
                        Sno.
                    </th>
                    <th>
                        {{ trans('cruds.contact.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.contact.email') }}
                    </th>

                    <th>
                        {{ trans('cruds.contact.number') }}
                    </th>
                    <th>
                        {{ trans('cruds.contact.company_name') }}
                    </th>
                    <th class="action">
                        {{ trans('cruds.action.title') }}
                    </th>
                </tr>
              </thead>
              <tbody>
                @php $i =1 @endphp
                @foreach($contact as $key => $contact)
                    <tr data-entry-id="{{ $contact->id }}">
                        {{-- <td>

                        </td> --}}
                        <td>
                            {{ $i }}
                        </td>
                        <td>
                            {{ $contact->name ?? '' }}
                        </td>
                        <td>
                            {{ $contact->email ?? '' }}
                        </td>

                        <td>
                            {{ $contact->number ?? '' }}
                        </td>
                        <td>
                            {{ $contact->company_name ?? '' }}
                        </td>


                        <td>
                            <div class="actionBtnGroup">
                                @can('hotel_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.contacts.show', $contact->id) }}">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                @endcan

                                @can('company_edit')
                                    {{-- <a class="btn btn-xs btn-info" href="{{ route('admin.page.edit', $contact->id) }}">
                                        {{ trans('global.edit') }}
                                    </a> --}}
                                @endcan

                                @can('company_delete')
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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































