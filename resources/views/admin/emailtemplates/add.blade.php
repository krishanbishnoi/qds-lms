@extends('admin.layouts.default')
@section('content')
    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($emailTemplate) && !empty($emailTemplate)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">{{ $heading }} Email Template</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                class=" fa fa-dashboard"></i>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route($modelName . '.index') }}">Email Template</a></li>
                    <li class="breadcrumb-item active">{{ $heading }} Email Template</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if ($flag == 1)
                            {{ Form::model($emailTemplate, ['url' => route("$modelName.save"), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route("$modelName.save"), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                        @endif

                        <div class="mws-panel-body no-padding tab-content row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('name', trans('Name') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('subject') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('subject', trans('Subject') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::text('subject', null, ['class' => 'form-control']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('subject'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('action', trans('Action'), ['class' => 'mws-form-label']) !!}
                                    <div class="mws-form-item">
                                        {{ Form::select('action', $Action_options, null, ['class' => 'form-control', 'onchange' => 'constant()']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('action'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('constants') ? 'has-error' : '' }}">
                                    {!! Html::decode(
                                        Form::label('constants', 'Constants'. '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}

                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Select dropdown --}}
                                        <div class="flex-grow-1">
                                            {{ Form::select('constants', [], '', [
                                                'placeholder' => 'Select one',
                                                'class' => 'form-control',
                                                'id' => 'constants',
                                            ]) }}
                                        </div>

                                        {{-- Insert button --}}
                                        <a onclick="return InsertHtml()" href="javascript:void(0)"
                                            class="btn btn-success btn-sm ">
                                            {{ trans('Insert Variable') }}
                                        </a>
                                    </div>

                                    @if ($errors->has('constants'))
                                        <div class="error-message text-danger mt-1">
                                            {{ $errors->first('constants') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group <?php echo $errors->first('body') ? 'has-error' : ''; ?>">
                                    {!! Html::decode(
                                        Form::label('body', trans('Email Body') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::textarea('body', null, ['class' => 'form-control', 'id' => 'body']) }}
                                        <span class="error-message help-inline">
                                            <?php echo $errors->first('body'); ?>
                                        </span>
                                    </div>
                                    <script>
                                        var body = CKEDITOR.replace('body', {
                                            extraAllowedContent: 'div',
                                            height: 300
                                        });
                                        body.on('instanceReady', function() {
                                            // Output self-closing tags the HTML4 way, like <br>.
                                            this.dataProcessor.writer.selfClosingEnd = '>';

                                            // Use line breaks for block elements, tables, and lists.
                                            var dtd = CKEDITOR.dtd;
                                            for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd
                                                    .$tableContent)) {
                                                this.dataProcessor.writer.setRules(e, {
                                                    indent: true,
                                                    breakBeforeOpen: true,
                                                    breakAfterOpen: true,
                                                    breakBeforeClose: true,
                                                    breakAfterClose: true,
                                                    filebrowserUploadUrl: '<?php echo URL::to('/admin/base/uploder'); ?>',
                                                    filebrowserImageWindowWidth: '640',
                                                    filebrowserImageWindowHeight: '480',
                                                });
                                            }
                                            // Start in source mode.
                                            //this.setMode('source');
                                        });
                                    </script>
                                </div>
                            </div>


                            <div class="mws-button-row text-end">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">

                                <a href="{{ URL::to('/admin/email-manager/add-template') }}" class="btn btn-primary"><i
                                        class=\"icon-refresh\"></i> {{ trans('Reset') }}</a>

                                <a href="{{ URL::to('/admin/email-manager') }}" class="btn btn-info"><i
                                        class=\"icon-refresh\"></i> {{ trans('Cancel') }}</a>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <style>
                            .table>thead>tr>th,
                            .table>tbody>tr>th,
                            .table>tfoot>tr>th,
                            .table>thead>tr>td,
                            .table>tbody>tr>td,
                            .table>tfoot>tr>td {
                                font-size: 14px !important;
                                padding: 0px !important;
                            }

                            .table>thead>tr>th,
                            .table>tbody>tr>th,
                            .table>tfoot>tr>th,
                            .table>thead>tr>td,
                            .table>tbody>tr>td,
                            .table>tfoot>tr>td {
                                vertical-align: top !important;
                            }

                            .table-bordered>thead>tr>th,
                            .table-bordered>tbody>tr>th,
                            .table-bordered>tfoot>tr>th,
                            .table-bordered>thead>tr>td,
                            .table-bordered>tbody>tr>td,
                            .table-bordered>tfoot>tr>td {
                                border: 0px !important;
                            }

                            .table>thead>tr>th,
                            .table>tbody>tr>th,
                            .table>tfoot>tr>th,
                            .table>thead>tr>td,
                            .table>tbody>tr>td,
                            .table>tfoot>tr>td {
                                border-top: 0px !important;
                                padding: 0px !important;
                            }

                            .table-bordered {
                                border: 0px !important;
                            }
                        </style>


                        <?php $constant = ''; ?>
                        <script type='text/javascript'>
                            var myText = '<?php echo $constant; ?>';
                            $(function() {
                                constant();
                            });
                            /* this function used for  insert contant, when we click on  insert variable button */
                            function InsertHtml() {

                                var strUser = document.getElementById("constants").value;

                                if (strUser != '') {
                                    var newStr = '{' + strUser + '}';
                                    var oEditor = CKEDITOR.instances["body"];
                                    oEditor.insertHtml(newStr);
                                }
                            }
                            /* this function used for get constant,define in email template*/
                            function constant() {
                                var constant = document.getElementById("action").value;
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    },
                                    url: "<?php echo URL::to('/admin/email-manager/get-constant'); ?>",
                                    type: "POST",
                                    data: {
                                        constant: constant
                                    },
                                    dataType: 'json',
                                    success: function(r) {
                                        $('#constants').empty();
                                        $('#constants').append('<option value="">-- Select One --</option>');
                                        $.each(r, function(val, text) {
                                            var sel = '';
                                            if (myText == text) {
                                                sel = 'selected="selected"';
                                            }

                                            $('#constants').append('<option value="' + text + '" ' + sel + '>' + text +
                                                '</option>');
                                        });
                                    }
                                });
                                return false;
                            }
                        </script>
                    @stop
