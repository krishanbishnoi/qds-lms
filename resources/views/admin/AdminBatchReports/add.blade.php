@extends('admin.layouts.default')
@section('content')
    <!-- JS & CSS library of MultiSelect plugin -->
    <!-- CSS for Select2 -->
    <script src="{{ asset('all-cdn/ckeditor.js') }}"></script>
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">Add New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item"><a href="#">Forms</a></li> -->
                    <!-- <li class="breadcrumb-item active" aria-current="page">Forget Password</li> -->

                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                class=" fa fa-dashboard"></i>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($modelName . '.index') }}">{{ $sectionName }}</a></li>
                    <li class="breadcrumb-item active">Add New {{ $sectionNameSingular }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['role' => 'form', 'route' => "$modelName.save", 'class' => 'mws-form', 'files' => true, 'autocomplete' => 'off']) }}
                        @csrf
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
                                        {{ Form::text('report_name', '', ['class' => 'form-control', 'required' => 'required']) }}
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
                                        {{ Form::text('report_type', '', ['class' => 'form-control', 'required' => 'required']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('report_type'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('report_from') ? 'has-error' : '' }}" id="live_date">
                                <div class="mws-form-row">
                                    {!! Html::decode( Form::label('report_from', trans('Report Date') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('report_from', null, ['class' => 'form-control small', 'id' => 'start_date_time', 'required' => 'required', 'autocomplete' => 'off']) }}
                                        <div class="error-message help-inline">
                                            {{ $errors->first('report_from') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--  <div class="form-group {{ $errors->first('report_to') ? 'has-error' : '' }}" id="live_date">
                                <div class="mws-form-row">
                                    {!!  Html::decode(Form::label('report_to', trans('Report to') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('report_to', null, ['class' => 'form-control small', 'id' => 'end_date_time', 'autocomplete' => 'off']) }}
                                        <div class="error-message help-inline">
                                            {{ $errors->first('report_to') }}
                                        </div>
                                    </div>
                                </div>
                            </div>  --}}

{{--
                            {!!  Html::decode(
                                Form::label('training_documents', trans('Upload Batch Report Documents') . '<span class="requireRed"> * </span>', [
                                    'class' => 'mws-form-label',
                                ]), ) !!}  --}}

                                                 <div class=" project_detailSection">
                                                        <div class="projectDetailsInnerSection ace_left_sec">
                                                            <table>
                                                                <tr>
                                                                    <td width="700px">
                                                                        <div class="form-group <?php echo $errors->first('document') ? 'has-error' : ''; ?>">
                                                                            {!! Html::decode(
                                                                                Form::label(
                                                                                    'document',
                                                                                    trans('Upload Training Report Document') .
                                                                                        '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    class="requireRed"> </span>',
                                                                                    ['class' => 'mws-form-label'],
                                                                                ),
                                                                            ) !!}
                                                                            <div class="mws-form-item">
                                                                                {{ Form::file('data[1][document]', ['class' => 'form-control document',  'required' => 'required',]) }}
                                                                                <div class="error-message help-inline">
                                                                                    <?php echo $errors->first('document'); ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    {{--  <td style="padding-top: 23px;" width="5px">
                                                                        <div class="form-group">
                                                                            <input type="hidden" name="count" value="1" id="add_more_count">
                                                                            <a href="javascript:void(0);" id="addMore"
                                                                                class="btn btn-primary add_new_btn add_more_new_supp"
                                                                                value="Add More">Add More</a>
                                                                        </div>
                                                                    </td>  --}}
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>


                            <div class="mws-button-row">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href="{{ route($modelName . '.add') }}" class="btn btn-primary reset_form"><i
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
                    <style>
                        .datetimepicker {
                            position: relative;
                        }
                    </style>

                    <script type="text/javascript">
                       $(document).ready(function() {
                            $('#trainees').selectpicker({
                                style: 'btn-default',
                                size: 4,
                                noneSelectedText: 'Select Users'
                            });
                        });
                        // jQuery('#trainees').multiselect({
                        //     //	columns: 1,
                        //     placeholder: 'Please Select Users',
                        //     search: true

                        // });




                        $(function() {

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
                                useCurrent: false,
                                minDate: moment() // Set the minimum date to today's date

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
                                useCurrent: false,
                                minDate: moment() // Set the minimum date to today's date
                            });

                            $("#start_date_time").on("dp.change", function(e) {
                                $('#end_date_time').data("DateTimePicker").minDate(e.date);
                            });
                            $("#end_date_time").on("dp.change", function(e) {
                                $('#start_date_time').data("DateTimePicker").maxDate(e.date);
                            });
                        });



                        $('#addMore').click(function() {
                            var count = $('#add_more_count').val();
                            var new_count = parseInt(count) + parseInt(1);
                            $.ajax({
                                url: '{{ URL('trainer/report/add-more-document') }}',
                                type: 'post',

                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "offset": new_count
                                },
                                async: false,
                                success: function(r) {
                                    if (r) {
                                        $('#add_more_count').val(new_count);
                                        $('.project_detailSection').append(r);
                                    } else {
                                        alert('There is an error please try again.')
                                    }
                                    $('#loader_img').hide();
                                }
                            });
                        });

                        function removeSection(id) {
                            bootbox.confirm("Are you sure want to remove this?",
                                function(result) {
                                    if (result) {
                                        $('.projectDetailsInnerSection_' + id).remove();
                                    }
                                });
                        }
                    </script>
                @stop
