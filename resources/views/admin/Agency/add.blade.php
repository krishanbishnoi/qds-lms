@extends('admin.layouts.default')
@section('content')

    <script src="https://cdn.ckeditor.com/4.15.0/standard-all/ckeditor.js"></script>

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
            <h2 class="page-title">{{ $heading }} New Agency</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('Agency.index') }}">Agency</a></li>
                    <li class="breadcrumb-item active">{{ $heading }} New Agency</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        @if ($flag == 1)
                            {{ Form::model($model, ['url' => route('Agency.save'), 'id' => 'edit-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route('Agency.save'), 'id' => 'add-form', 'class' => 'row g-3']) }}
                        @endif

                        <div class="mws-panel-body no-padding tab-content row">

                            {{-- NAME --}}
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('name', 'Agency Name', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter agency name', 'required']) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('name') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('email') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('email', 'Email', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email', 'required']) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('email') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- MOBILE --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('mobile_no') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('mobile_no', 'Mobile Number', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::text('mobile_no', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter mobile number',
                                                'required',
                                            ]) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('mobile_no') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- AGENCY CODE --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('agency_code') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('agency_code', 'Agency Code', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::text('agency_code', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter agency code',
                                                'required',
                                            ]) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('agency_code') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- REGION --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('region_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('region_id', 'Region', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::select('region_id', $regions, null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Select Region',
                                                'required',
                                            ]) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('region_id') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- STATE --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('state_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('state_id', 'State', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::select('state_id', $states, null, [
                                                'class' => 'form-control',
                                                'id' => 'state_id',
                                                'placeholder' => 'Select State',
                                                'required',
                                            ]) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('state_id') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CITY --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('city_id') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('city_id', 'City', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::select('city_id', $cities, null, [
                                                'class' => 'form-control',
                                                'id' => 'city_id',
                                                'placeholder' => 'Select City',
                                                'required',
                                            ]) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('city_id') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                        {{-- ADDRESS --}}
                            <div class="col-md-6">
                                <div class="form-group <?php echo $errors->first('address') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter address']) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('address') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('name', 'Agency Manager Name', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter agency name', 'required']) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('name') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class=" form-group <?php echo $errors->first('name') ? 'has-error' : ''; ?>">
                                    <div class="mws-form-row">
                                        {!! Form::label('name', 'Agency Manager Email', ['class' => 'form-label required']) !!}
                                        <div class="mws-form-item">
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter agency name', 'required']) !!}
                                            <div class="error-message help-inline">
                                                {{ $errors->first('name') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                

                            {{-- BUTTONS --}}
                            <div class="mws-button-row text-end">
                                <input type="submit" value="{{ trans('Save') }}" class="btn btn-danger">
                                <a href="{{ route('Agency.add') }}" class="btn btn-primary reset_form">
                                    <i class="icon-refresh"></i> {{ trans('Clear') }}
                                </a>
                            </div>

                        </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            @if (isset($model) && $model->city_id)
                $('#city_id').val("{{ $model->city_id }}");
            @endif
            $('#state_id').on('change', function() {
                let stateId = $(this).val();

                if (!stateId) {
                    $('#city_id').html('<option value="">Select City</option>');
                    return;
                }

                $.ajax({
                    url: "{{ route('Agency.getCities', ['state' => 'STATE_ID']) }}".replace(
                        'STATE_ID',
                        stateId),
                    type: "GET",
                    success: function(data) {
                        $('#city_id').empty();
                        $('#city_id').append('<option value="">Select City</option>');

                        $.each(data, function(key, value) {
                            $('#city_id').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });

            });

        });
    </script>



@stop
