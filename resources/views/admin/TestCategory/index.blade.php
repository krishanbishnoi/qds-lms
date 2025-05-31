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
        <div class="box search-panel collapsed-box">

            <div class="box-body">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row mws-form']) }}
                {{ Form::hidden('display') }}
                <div class="col-md-2 col-sm-2">
                    <div class="form-group ">
                        {!! Html::decode(
                            Form::label('title', trans('Title') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('title', isset($searchVariable['title']) ? $searchVariable['title'] : '', ['class' => 'form-control', 'placeholder' => 'Category Name']) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 paddingtop">
                    <div class="d-flex">
                        <button class="btn btn-primary mr-2 px-4"><i class='fa fa-search '></i> Search</button>
                        <a href='{{ route("$modelName.index") }}' class="btn btn-primary"> <i class="fa fa-refresh "></i>
                            {{ trans('Clear Search') }}</a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="box-header with-border pd-custom">
                            <div class="listing-btns">
                                <h1 class="box-title">{{ $sectionName }} List</h1>
                                <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                            </div>
                        </div>
                        <table class="table table-hover brdrclr mt-2">
                            <thead class="theadLight">
                                <tr>
                                    <th width="30%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Name'),
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
                                    <th width="25%"> Status</th>
                                    <th width="30%">
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Modified'),
                                            [
                                                'sortBy' => 'updated_at',
                                                'order' => $sortBy == 'updated_at' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'updated_at' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'updated_at' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th width="30%">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @foreach ($results as $record)
                                        <tr class="items-inner">
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->name }}</td>
                                            <td> <span
                                                    class="badge {{ $record->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$record->is_active] ?? 'Unknown Status' }}
                                                </span></td>

                                            <td data-th="{{ trans('Page Name') }}">{{ $record->updated_at }}</td>
                                            <td data-th='' class="action-td">
                                                <a href='{{ route("$modelName.edit", "$record->id") }}'
                                                    class="btn btn-primary" title="Edit"> <span
                                                        class="fas fa-edit"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="alignCenterClass" colspan="4">{{ trans('No Record Found') }}</td>
                                    </tr>
                                @endif
                            </tbody>


                        </table>
                        <div class="box-footer clearfix">
                            <!-- <div class="col-md-3 col-sm-4 "></div> -->
                            <div class="col-md-12 col-sm-12 text-right ">@include('pagination.default', ['paginator' => $results])</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <div class="modal fade" id="AssignManagerModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Test Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['role' => 'form', 'url' => '', 'class' => 'mws-form', 'id' => 'AssignManagerForm', 'files' => true, 'autocomplete' => 'off']) }}

                <div class="modal-body">

                    <!-- Your form group with the multiselect dropdown -->
                    <div class="form-group">
                        {{ Form::hidden('test_id', '', ['class' => 'form-control', 'id' => 'test_id']) }}
                        <div class="mws-form-row">
                            {!! Html::decode(
                                Form::label(
                                    'training_manager',
                                    trans('Assign Test Manager') .
                                        '<span
                                                                                                                                                                                                                                class="requireRed"></span>',
                                    ['class' => 'mws-form-label'],
                                ),
                            ) !!}
                            <div class="mws-form-item">
                                {{ Form::select('training_manager[]', $training_manager, '', ['class' => 'form-control', 'id' => 'training_manager', 'multiple']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('training_manager'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Assign</button> -->
                    <input type="button" value="{{ trans('Assign') }}" onclick="AssignManagerSave();"
                        class="btn btn-danger">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="AssignTrainerModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Trainer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{ Form::open(['role' => 'form', 'url' => '', 'class' => 'mws-form', 'id' => 'AssignTrainerForm', 'files' => true, 'autocomplete' => 'off']) }}

                <div class="modal-body">

                    <!-- Your form group with the multiselect dropdown -->
                    <div class="form-group">
                        {{ Form::hidden('test_id', '', ['class' => 'form-control', 'id' => 'training_test_id']) }}
                        <div class="mws-form-row">
                            {!! Html::decode(
                                Form::label(
                                    'training_trainer',
                                    trans('Assign Trainer') .
                                        '<span
                                                                                                                                                                                                                                class="requireRed"></span>',
                                    ['class' => 'mws-form-label'],
                                ),
                            ) !!}
                            <div class="mws-form-item">
                                {{ Form::select('training_trainer[]', $trainers, '', ['class' => 'form-control', 'id' => 'training_trainer', 'multiple']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('training_trainer'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Assign</button> -->
                    <input type="button" value="{{ trans('Assign') }}" onclick="AssignTrainerSave();"
                        class="btn btn-danger">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // When the modal is shown
            $('#AssignManagerModel').on('shown.bs.modal', function() {
                // Add the select2-container--open class to the modal
                $(this).addClass('select2-container--open');
            });

            // When the modal is hidden
            $('#AssignManagerModel').on('hidden.bs.modal', function() {
                // Remove the select2-container--open class from the modal
                $(this).removeClass('select2-container--open');
            });
            $('#AssignTrainerModel').on('shown.bs.modal', function() {
                // Add the select2-container--open class to the modal
                $(this).addClass('select2-container--open');
            });

            // When the modal is hidden
            $('#AssignTrainerModel').on('hidden.bs.modal', function() {
                // Remove the select2-container--open class from the modal
                $(this).removeClass('select2-container--open');
            });

        });

        $('#training_manager').select2({
            width: '100%', // Adjust the width of the dropdown
        });

        $('#training_trainer').select2({
            width: '100%', // Adjust the width of the dropdown
        });

        function AssignManager(test_id, selected_training_manager) {
            $('#test_id').val(test_id);
            $('#training_manager').val(selected_training_manager);
            $("#AssignManagerModel").modal('show');
        }

        function AssignTrainer(test_id, selected_training_trainer) {
            $('#training_test_id').val(test_id);
            $('#training_trainer').val(selected_training_trainer);
            $("#AssignTrainerModel").modal('show');
        }


        function AssignManagerSave() {
            var dataString = $("#AssignManagerForm").serialize();
            $.ajax({
                url: '{{ url('admin/tests/assign-manager') }}',
                type: 'post',
                data: dataString,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(r) {
                    error_array = JSON.stringify(r);
                    data = JSON.parse(error_array);
                    if (data['success'] == '1') {
                        toastr.success('Invalid Request.');
                    } else {
                        //	location.reload();
                        $('#AssignManagerModel').modal('hide');
                        toastr.success('Manager assign successfully');
                    }
                }
            });
        }

        function AssignTrainerSave() {
            var dataString = $("#AssignTrainerForm").serialize();
            $.ajax({
                url: '{{ url('admin/tests/assign-trainer') }}',
                type: 'post',
                data: dataString,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(r) {
                    error_array = JSON.stringify(r);
                    data = JSON.parse(error_array);
                    if (data['success'] == '1') {
                        toastr.success('Invalid Request.');
                    } else {
                        //	location.reload();
                        $('#AssignTrainerModel').modal('hide');
                        toastr.success('Trainer assign successfully');
                    }
                }
            });
        }
    </script>
    <style>
        .paddingtop {
            padding-top: 20px;
        }

        .select2-container--open {
            z-index: 1060;
            /* Adjust this value if needed */
        }
    </style>
@stop
