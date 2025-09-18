@extends('admin.layouts.default')
@section('content')
    <script src="{{ asset('all-cdn/ckeditor.js') }}"></script>

    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">Edit {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>

                    <li class="breadcrumb-item"><a href='{{ route("$modelName.index") }}'>{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['role' => 'form', 'url' => route("$modelName.update", $model->id), 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                        @csrf
                        @method('POST')
                        <div class="mws-panel-body no-padding tab-content">
                            <div class=" form-group <?php echo $errors->first('report_name') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'report_name',
                                            trans('Report Name') .
                                                '<span class="requireRed">*
                                                                    </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('report_name', $model->report_name, ['class' => 'form-control' , 'required' => 'required']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('report_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" form-group <?php echo $errors->first('report_type') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'report_type',
                                            trans('Report Type') .
                                                '<span class="requireRed">*
                                                                    </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('report_type', $model->report_type, ['class' => 'form-control', 'required' => 'required']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('report_type'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?php echo $errors->first('report_from') ? 'has-error' : ''; ?>" id="live_date">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'report_from',
                                            trans('Report Date') .
                                                '<span
                                                                        class="requireRed"> * </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('report_from', $model->report_from, ['class' => 'form-control small', 'required' => 'required', 'id' => 'start_date_time']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('report_from'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--  <div class="form-group <?php echo $errors->first('report_to') ? 'has-error' : ''; ?>"
                            id="live_date">
                            <div class="mws-form-row">
                                {!! Html::decode( Form::label('report_to', trans("Report to").'<span
                                    class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                <div class="mws-form-item">
                                    {{ Form::text('report_to',$model->report_to, ['class' => 'form-control small', 'id' => 'end_date_time' ]) }}
                                    <div class="error-message help-inline">
                                        <?php echo $errors->first('report_to'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>  --}}

                            {{--  {!! Html::decode(
                            Form::label(
                                'report',
                                trans('Report Documents') .
                                    '<span
                                                    class="requireRed"> * </span>',
                                ['class' => 'mws-form-label'],
                            ),
                        ) !!}  --}}
                            <div class="project_detailSection">
                                <?php $i = 0; ?>
                                @if (!$documents->isEmpty())
                                    @foreach ($documents as $document)
                                        <?php $i++; ?>
                                        <div class="projectDetailsInnerSection_<?php echo $i; ?> ace_left_sec">
                                            <table>
                                                <tr>
                                                    <td width="700px">
                                                        <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                                                            {!! Html::decode(
                                                                Form::label(
                                                                    'document',
                                                                    trans('Training Report Documents') .
                                                                        '<span
                                                                                                                                                                        class="requireRed"> </span>',
                                                                    ['class' => 'mws-form-label'],
                                                                ),
                                                            ) !!}
                                                            <div class="mws-form-item">
                                                                {{ Form::file('data[' . $i . '][document]', ['class' => 'form-control ']) }}
                                                                <div class="error-message help-inline">
                                                                    <?php echo $errors->first('document'); ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="700px">
                                                        <div>
                                                            @if ($document->type == 'image')
                                                                @if (config('BATCH_REPORT_URL') . $document->document != '')
                                                                    <br />
                                                                    <img height="50%" width="50%"
                                                                        src="{{ config('BATCH_REPORT_URL') . $document->document }}" />
                                                                @endif
                                                            @elseif ($document->type == 'doc')
                                                                @if (config('BATCH_REPORT_FILES') . $document->document != '')
                                                                    <br />
                                                                    <iframe
                                                                        src="{{ config('BATCH_REPORT_URL') . $document->document }}"
                                                                        style="width: 100%; height: 500px;"></iframe>
                                                                @endif
                                                            @else($document->type == 'video')
                                                                @if (config('BATCH_REPORT_URL') . $document->document != '')
                                                                    <br />
                                                                    <video height="50%" width="50%" controls>
                                                                        <source
                                                                            src="{{ config('BATCH_REPORT_URL') . $document->document }}"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                    {{--  @if ($i == 1)
                                                    <td style=" padding-top: 20px;" width="25px">
                                                        <div class="form-group">
                                                            <a href="javascript:void(0);" id="addMore"
                                                                class="btn btn-primary add_new_btn" value="Add More">Add
                                                                More</a>
                                                        </div>

                                                    </td>
                                                @else
                                                    <td style=" padding-top: 20px;" width="25px">
                                                        <div class="form-group">
                                                            <input type="hidden" name="data[<?php echo $i; ?>][entryID]"
                                                                id="entryID_<?php echo $i; ?>"
                                                                value="{{ $document->id }}">
                                                            <?php if($i !=1){?>
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-success position-right option"
                                                                onclick="removeTableEntry('<?php echo $i; ?>')"
                                                                id="remove_'+new_count+'">Remove</a>
                                                            <?php } ?>
                                                    </td>
                                                @endif  --}}

                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>

                                                        <!-- @if ($document->document != '')
    @if ($document->type == 'image')
    <img height="50" width="50" src="{{ config('TRAINING_DOCUMENT_URL') . $document->document }}" />
@elseif($document->type == 'video')
    <br />
                                                        <video width="400" controls>
                                                        <source src="{{ config('TRAINING_DOCUMENT_URL') . $document->document }}" type="video/mp4">

                                                        </video>
@else
    <iframe  eight="50" width="50" src="{{ config('TRAINING_DOCUMENT_URL') . $document->document }}" frameborder="0"></iframe>
    @endif
    @endif -->


                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="count" value="<?php echo $i; ?>" id="add_more_count">
                                @else
                                    <div class="projectDetailsInnerSection">
                                        <table>
                                            <tr>
                                                <td width="700px">
                                                    <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                                                        {!! Html::decode(
                                                            Form::label('document', trans('Document') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label']),
                                                        ) !!}
                                                        <div class="mws-form-item">
                                                            {{ Form::file('data[' . $i . '][document]', ['class' => 'form-control ']) }}
                                                            <div class="error-message help-inline">
                                                                <?php echo $errors->first('document'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                        </table>
                                    </div>
                                    <input type="hidden" name="count" value="1" id="add_more_count">
                                @endif

                            </div>


                            <div class="mws-button-row">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href='{{ route("$modelName.edit", [$model->id]) }}' class="btn btn-primary reset_form"><i
                                        class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                                <a href="{{ route($modelName . '.index') }}" class="btn btn-info"><i
                                        class=\"icon-refresh\"></i> {{ trans('Cancel') }}</a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <style>
                        .datetimepicker {
                            position: relative;
                        }
                    </style>
                    <script type="text/javascript">
                        $(function() {
                            // Initialize date time pickers
                            $('#start_date_time').datetimepicker({
                                format: 'YYYY-MM-DD HH:mm:ss',
                                icons: {
                                    time: "fa fa-clock-o",
                                    date: "fa fa-calendar",
                                    up: "fa fa-arrow-up",
                                    down: "fa fa-arrow-down",
                                    previous: "fa fa-chevron-left",
                                    next: "fa fa-chevron-right",
                                    today: "fa fa-clock-o",
                                    clear: "fa fa-trash-o"
                                },
                                useCurrent: false
                            });

                            $('#end_date_time').datetimepicker({
                                format: 'YYYY-MM-DD HH:mm:ss',
                                icons: {
                                    time: "fa fa-clock-o",
                                    date: "fa fa-calendar",
                                    up: "fa fa-arrow-up",
                                    down: "fa fa-arrow-down",
                                    previous: "fa fa-chevron-left",
                                    next: "fa fa-chevron-right",
                                    today: "fa fa-clock-o",
                                    clear: "fa fa-trash-o"
                                },
                                useCurrent: false
                            });

                            // Update minDate for end_date_time on change of start_date_time
                            $("#start_date_time").on("dp.change", function(e) {
                                $('#end_date_time').data("DateTimePicker").minDate(e.date);
                            });

                            // Update maxDate for start_date_time on change of end_date_time
                            $("#end_date_time").on("dp.change", function(e) {
                                $('#start_date_time').data("DateTimePicker").maxDate(e.date);
                            });

                            // Handle click event for add more button
                            $(document).on('click', '#addMore', function() {
                                var count = $('#add_more_count').val();
                                var new_count = parseInt(count) + 1;
                                $.ajax({
                                    url: '{{ URL('trainer/report/add-more-document') }}',
                                    type: 'post',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "offset": new_count
                                    },
                                    success: function(r) {
                                        if (r) {
                                            $('#add_more_count').val(new_count);
                                            $('.project_detailSection').append(r);
                                        } else {
                                            alert('There is an error, please try again.');
                                        }
                                        $('#loader_img').hide();
                                    }
                                });
                            });

                            // Function to remove section
                            window.removeSection = function(id) {
                                bootbox.confirm("Are you sure you want to remove this?", function(result) {
                                    if (result) {
                                        $('.projectDetailsInnerSection_' + id).remove();
                                    }
                                });
                            };

                            // Function to remove table entry
                            window.removeTableEntry = function(id) {
                                bootbox.confirm("Are you sure you want to remove this?", function(result) {
                                    if (result) {
                                        $('#loader_img').show();
                                        var entID = $('#entryID_' + id).val();
                                        $.ajax({
                                            url: '{{ URL('trainer/courses/delete-more-document') }}',
                                            type: 'post',
                                            data: {
                                                "_token": "{{ csrf_token() }}",
                                                'id': entID
                                            },
                                            success: function(r) {
                                                if (r == 1) {
                                                    $('.projectDetailsInnerSection_' + id).remove();
                                                } else {
                                                    alert('There is an error, please try again.');
                                                }
                                                $('#loader_img').hide();
                                            }
                                        });
                                    }
                                });
                            };

                            // Show/hide row_dim based on question_type value
                            $('#row_dim').hide();
                            $('#question_type').change(function() {
                                if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                                        '#question_type').val() == 'select') {
                                    $('#row_dim').show();
                                } else {
                                    $('.option').val("");
                                    $('#row_dim').hide();
                                }
                            });

                            // Initial check for question_type on document ready
                            if ($('#question_type').val() == 'radio' || $('#question_type').val() == 'checkbox' || $(
                                    '#question_type').val() == 'select') {
                                $('.question_type').show();
                            }
                        });
                    </script>
                @stop
