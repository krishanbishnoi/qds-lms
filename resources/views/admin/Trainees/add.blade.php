@extends('admin.layouts.default')
@section('content')
    <script>
        $(function() {
            $('#date_of_birth').datetimepicker({
                format: 'YYYY-MM-DD',
                maxDate: new Date(),
                icons: {
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                }
            });

        });
    </script>
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($model) && !empty($model)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp
    <div class="content-wrapper">
        <div class="page-header">
            <h2 class="page-title">{{ $heading }} New {{ $sectionNameSingular }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
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

                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route("$modelName.save"), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route("$modelName.save"), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                        @endif
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('employee_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'employee_id',
                                                trans('Employee ID') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('employee_id', null, ['class' => 'form-control small']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('employee_id'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('first_name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'first_name',
                                                trans('First Name') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('first_name', null, ['class' => 'form-control small']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('first_name'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('last_name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'last_name',
                                                trans('Last Name') .
                                                    '<span class="requireRed">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('last_name', null, ['class' => 'form-control small']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('last_name'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('email') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'email',
                                                trans('Email') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('email', null, ['class' => 'form-control small']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('email'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('mobile_number') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'mobile_number',
                                                trans('Mobile Number') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::text('mobile_number', null, ['class' => 'form-control small']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('mobile_number'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('designation') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'designation',
                                                trans('Designation') .
                                                    '<span
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                class="requireRed"> * </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('designation', $designation, null, ['class' => 'form-control ', 'placeholder' => 'Please Select Designation ']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('designation'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('lob') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label('lob', trans('LOB') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('lob', $lob, null, ['class' => 'form-control ', 'placeholder' => 'Please Select LOB ']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('lob'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('region') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'region',
                                                trans('Region') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('region', $region, null, ['class' => 'form-control ', 'placeholder' => 'Please Select Region ']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('region'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('circle') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Html::decode(
                                            Form::label(
                                                'circle',
                                                trans('Circle') .
                                                    '<span class="requireRed"> *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </span>',
                                                ['class' => 'mws-form-label'],
                                            ),
                                        ) !!}
                                        <div class="mws-form-item">
                                            {{ Form::select('circle', $circle, null, ['class' => 'form-control ', 'placeholder' => 'Please Select Circle ']) }}
                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('circle'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('gender') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label(
                                            'gender',
                                            trans('Gender') . '<span class="requireRed"> * </span>',
                                            ['class' => 'mws-form-label'],
                                            false,
                                        ) !!}
                                        <div class="mws-form-item">
                                            {!! Form::radio('gender', 'male', null, ['id' => 'gender_male']) !!}
                                            {!! Form::label('gender_male', 'Male') !!}

                                            {!! Form::radio('gender', 'female', null, ['id' => 'gender_female']) !!}
                                            {!! Form::label('gender_female', 'Female') !!}

                                            <div class="error-message help-inline">
                                                <?php echo $errors->first('gender'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row text-end">
                                <div class="col-md-12">
                                    <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">

                                    <a href="{{ route($modelName . '.add') }}" class="btn btn-primary reset_form"><i
                                            class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                                    <a href="{{ route($modelName . '.index') }}" class="btn btn-info"><i
                                            class=\"icon-refresh\"></i>
                                        {{ trans('Cancel') }}</a>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>

                    @stop
