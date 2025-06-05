@extends('admin.layouts.default')
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

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

            <div class="box-body filterGroup">
                {{ Form::open(['method' => 'get', 'role' => 'form', 'route' => "$modelName.index", 'class' => 'row align-items-end mws-form']) }}
                {{ Form::hidden('display') }}
                <div class="col">
                    <div class="form-group">
                        {!! Html::decode(
                            Form::label('title', trans('Title') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                        ) !!}
                        {{ Form::text('title', isset($searchVariable['title']) ? $searchVariable['title'] : '', ['class' => 'form-control', 'placeholder' => 'Test title']) }}
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex form-group gap-2">
                        <button class="btn btn-primary"><i class='fa fa-search me-1'></i> Search</button>
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
                                <h1 class="box-title">{{ $sectionName }}'s List</h1>
                                <a class="btn btn-success" href="{{ route('export.tests') }}">Download Tests</a>

                                <a href='{{ route("$modelName.add") }}' class="btn btn-success btn-small pull-right mb-2">
                                    {{ trans('Add New ') }}{{ $sectionNameSingular }} </a>
                            </div>
                        </div>
                        <table class="table table-hover table table-bordered mt-2 ">
                            <thead class="theadLight">
                                <tr>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Title'),
                                            [
                                                'sortBy' => 'title',
                                                'order' => $sortBy == 'title' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'title' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'title' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Test Category'),
                                            [
                                                'sortBy' => 'category_id',
                                                'order' => $sortBy == 'category_id' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'category_id' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'category_id' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                                        <th>
                                            {{ link_to_route(
                                                "$modelName.index",
                                                trans('Created By'),
                                                [
                                                    'sortBy' => 'created_by',
                                                    'order' => $sortBy == 'created_by' && $order == 'desc' ? 'asc' : 'desc',
                                                    $query_string,
                                                ],
                                                [
                                                    'class' =>
                                                        $sortBy == 'created_by' && $order == 'desc'
                                                            ? 'sorting desc'
                                                            : ($sortBy == 'created_by' && $order == 'asc'
                                                                ? 'sorting asc'
                                                                : 'sorting'),
                                                ],
                                            ) }}
                                        </th>
                                    @endif
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Test Type'),
                                            [
                                                'sortBy' => 'type',
                                                'order' => $sortBy == 'type' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'type' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'type' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    <!-- <th                                                                                               {{ link_to_route(
                                        "$modelName.index",
                                        trans('Minimum Marks'),
                                        [
                                            'sortBy' => 'minimum_marks',
                                            'order' => $sortBy == 'minimum_marks' && $order == 'desc' ? 'asc' : 'desc',
                                            $query_string,
                                        ],
                                        [
                                            'class' =>
                                                $sortBy == 'minimum_marks' && $order == 'desc'
                                                    ? 'sorting desc'
                                                    : ($sortBy == 'minimum_marks' && $order == 'asc'
                                                        ? 'sorting asc'
                                                        : 'sorting'),
                                        ],
                                    ) }}
                                                                                                                    </th> -->
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Start Date Time'),
                                            [
                                                'sortBy' => 'start_date_time',
                                                'order' => $sortBy == 'start_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'start_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'start_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('End Date Time'),
                                            [
                                                'sortBy' => 'end_date_time',
                                                'order' => $sortBy == 'end_date_time' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'end_date_time' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'end_date_time' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>
                                    <th>
                                        {{ link_to_route(
                                            "$modelName.index",
                                            trans('Status'),
                                            [
                                                'sortBy' => 'is_active',
                                                'order' => $sortBy == 'is_active' && $order == 'desc' ? 'asc' : 'desc',
                                                $query_string,
                                            ],
                                            [
                                                'class' =>
                                                    $sortBy == 'is_active' && $order == 'desc'
                                                        ? 'sorting desc'
                                                        : ($sortBy == 'is_active' && $order == 'asc'
                                                            ? 'sorting asc'
                                                            : 'sorting'),
                                            ],
                                        ) }}
                                    </th>

                                    {{-- <th  >
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
                                </th> --}}
                                    <th>{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                @if (!$results->isEmpty())
                                    @foreach ($results as $record)
                                        <tr class="items-inner">
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->title }}</td>

                                            <td data-th="{{ trans('Page Name') }}">{{ $record->category_name }}</td>

                                            @if (Auth::user()->user_role_id == SUPER_ADMIN_ROLE_ID)
                                                <td data-th="{{ trans('Page Name') }}">{{ $record->created_by }}</td>
                                            @endif

                                            @if ($record->type == 'training_test')
                                                <td data-th="{{ trans('Page Name') }}">{{ trans('Training Test') }}</td>
                                            @elseif ($record->type == 'regular_test')
                                                <td data-th="{{ trans('Page Name') }}">{{ trans('Regular Test') }}</td>
                                            @elseif ($record->type == 'feedback_test')
                                                <td data-th="{{ trans('Page Name') }}">{{ trans('Feedback Test') }}</td>
                                            @endif

                                            <!-- <td data-th="{{ trans('Page Name') }}">{{ $record->minimum_marks }}</td> -->
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->start_date_time }}</td>
                                            <td data-th="{{ trans('Page Name') }}">{{ $record->end_date_time }}</td>
                                            <td data-th='{{ trans('Status') }}'>
                                                @if ($record->status == 0)
                                                    <span class="badge text-danger">Upcoming</span>
                                                @elseif($record->status == 1)
                                                    <span class="badge text-warning">Ongoing</span>
                                                @else
                                                    <span class="badge text-success">Completed</span>
                                                @endif
                                            </td>

                                            {{-- <td data-th='{{ trans('Modified') }}'>
                                {{ date(Config::get('Reading.date_format'), strtotime($record->updated_at)) }}
                                </td> --}}
                                            <td data-th='' class="action-td">
                                                <!-- @if ($record->is_active == 1)
    <a  title="Click To Deactivate" href='{{ route("$modelName.status", [$record->id, 0]) }}' class="btn btn-success btn-small status_any_item "><span class="fa fa-ban"></span>
                                                                                             </a>
@else
    <a title="Click To Activate" href='{{ route("$modelName.status", [$record->id, 1]) }}' class="btn btn-warning btn-small status_any_item"><span class="fa fa-check"></span>
                                                                                             </a>
    @endif  -->

                                                <a href='{{ route("$modelName.edit", "$record->id") }}'
                                                    class="btn btn-primary" title="Edit"> <span
                                                        class="fas fa-edit"></span></a>


                                                <a href='{{ route("$modelName.delete", "$record->id") }}'
                                                    data-delete="delete" class="delete_any_item btn btn-danger"
                                                    title="Delete" data-confirm='Are you sure?'>
                                                    <span class="fas fa-trash-alt   "></span>
                                                </a>

                                                <a href='{{ route("$modelName.view", "$record->id") }}'
                                                    class="btn btn-warning" title="View"> <span
                                                        class="fas fa-eye"></span></a>
                                                <a href='{{ route('Question.index', "$record->id") }}' class="btn btn-info"
                                                    title="View Questions"> <span class="fa fa-question"></span></a>
                                                <a href='{{ route('import.importTestsParticipants', "$record->id") }}'
                                                    class="btn btn-success" title="Import Tests Participants"> <span
                                                        class="fa fa-plus"></span></a>

                                                <?php $selected_training_manager = DB::table('manager_assign_training')->where('test_id', $record->id)->pluck('user_id')->toArray();
                                                
                                                $selected_training_trainers = DB::table('trainer_assign_training')->where('test_id', $record->id)->pluck('user_id')->toArray();
                                                
                                                // echo '<pre>'; print_r($selected_training_manager);
                                                
                                                ?>

                                                @if (Auth::user()->user_role_id == MANAGER_ROLE_ID)
                                                    <a onclick="AssignTrainer({{ $record->id }}, {{ json_encode($selected_training_trainers) }})"
                                                        class="btn btn-success" title="Assign Trainers"> <span
                                                            class="fa fa-user"></span></a>
                                                @else
                                                    <a onclick="AssignManager({{ $record->id }}, {{ json_encode($selected_training_manager) }})"
                                                        class="btn btn-success" title="Assign Managers"> <span
                                                            class="fa fa-user"></span></a>
                                                @endif

                                                <a id="copyButton" class="btn btn-info" title="Get Test Link"
                                                    data-clipboard-text="{{ route('userTestDetails.index.link.copied', $record->id) }}">
                                                    <span class="fas fa-link"></span>
                                                </a>

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


    <div class="modal fade" id="AssignManagerModel" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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

    <div class="modal fade" id="AssignTrainerModel" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
        var clipboard = new ClipboardJS('#copyButton');

        clipboard.on('success', function(e) {
            console.log('Text copied to clipboard: ' + e.text);
            alert('Link copied to clipboard!');
            e.clearSelection();
        });

        clipboard.on('error', function(e) {
            alert('Unable to copy text to clipboard', e);
            e.clearSelection();
        });
    </script>
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
