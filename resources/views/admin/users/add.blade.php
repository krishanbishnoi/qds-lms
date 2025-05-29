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
                        {{ Form::open(['role' => 'form', 'route' => "$modelName.add", 'class' => 'forms-sample', 'files' => true, 'autocomplete' => 'off']) }}
                        <div class="form-group ">
                            <!-- <div class="form-group <?php echo $errors->first('user_role_id') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'user_role_id',
                                            trans('User Role') .
                                                '<span class="requireRed"> *
                                                                    </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::select('user_role_id', user_role, '', ['class' => 'form-control ']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('user_role_id'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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
                                        {{ Form::text('employee_id', '', ['class' => 'form-control small']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('employee_id'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::text('first_name', '', ['class' => 'form-control small']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('first_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::text('last_name', '', ['class' => 'form-control small']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('last_name'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::text('email', '', ['class' => 'form-control small']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('email'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::text('mobile_number', '', ['class' => 'form-control small']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('mobile_number'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::select('designation', $designation, '', ['class' => 'form-control ', 'placeholder' => 'Please Select Designation ']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('designation'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?php echo $errors->first('lob') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label('lob', trans('LOB') . '<span class="requireRed"> * </span>', ['class' => 'mws-form-label']),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::select('lob', $lob, '', ['class' => 'form-control ']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('lob'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::select('region', $region, '', ['class' => 'form-control ']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('region'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        {{ Form::select('circle', $circle, '', ['class' => 'form-control ']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('circle'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group <?php echo $errors->first('password') ? 'has-error' : ''; ?>">
                            {!! Html::decode( Form::label('password', trans("Password").'<span class="requireRed"> *
                            </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::password('password',['class'=>'userPassword form-control']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('password'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group <?php echo $errors->first('confirm_password') ? 'has-error' : ''; ?>">
                            {!! Html::decode( Form::label('confirm_password', trans("Confirm Password").'<span
                                class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
                            <div class="mws-form-item">
                                {{ Form::password('confirm_password',['class'=>'form-control']) }}
                                <div class="error-message help-inline">
                                    <?php echo $errors->first('confirm_password'); ?>
                                </div>
                            </div>
                        </div> --}}
                            <div class="form-group <?php echo $errors->first('Gender') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label(
                                            'gender',
                                            trans('Gender') .
                                                '<span class="requireRed"> *
                                                                                                    </span>',
                                            ['class' => 'mws-form-label'],
                                        ),
                                    ) !!}
                                    <div class="mws-form-item">
                                        <input type="radio" name="gender" value="male" id="gender_male">
                                        <label for="gender_male">Male</label>

                                        <input type="radio" name="gender" value="female" id="gender_female">
                                        <label for="gender_female">Female</label>
                                        <div class="error-message help-inline">

                                            <?php echo $errors->first('gender'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?php echo $errors->first('allow_create_training') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label('allow_create_training', trans('Allow Create Training') . '<span class="requireRed"> * </span>', [
                                            'class' => 'mws-form-label',
                                        ]),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::checkbox('allow_create_training', '1', false, ['class' => '']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('allow_create_training'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group <?php echo $errors->first('allow_create_test') ? 'has-error' : ''; ?>">
                                <div class="mws-form-row">
                                    {!! Html::decode(
                                        Form::label('allow_create_test', trans('Allow Create Test') . '<span class="requireRed"> * </span>', [
                                            'class' => 'mws-form-label',
                                        ]),
                                    ) !!}
                                    <div class="mws-form-item">
                                        {{ Form::checkbox('allow_create_test', '1', false, ['class' => '']) }}
                                        <div class="error-message help-inline">
                                            <?php echo $errors->first('allow_create_test'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                            <a href="{{ route($modelName . '.add') }}" class="btn btn-primary reset_form"><i
                                    class=\"icon-refresh\"></i> {{ trans('Clear') }}</a>
                            <a href="{{ route($modelName . '.index') }}" class="btn btn-info"><i class=\"icon-refresh\"></i>
                                {{ trans('Cancel') }}</a>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                    @stop
