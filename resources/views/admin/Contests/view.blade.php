@extends('admin.layouts.default')
@section('content')

    <div class="content-wrapper">
        <div class="page-header">
            <h1>
                View {{ $sectionName }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-dashboard"></i>
                            Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $sectionName }}</li>
                </ol>
            </nav>
        </div>


        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover brdrclr" width="100%">
                            <h2>
                                {{ $sectionName }} Details
                            </h2>
                            <tbody>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Id</th>
                                    <td data-th='Name' class="txtFntSze">
                                        {{ isset($model->id) ? $model->id : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Name</th>
                                    <td data-th='Name' class="txtFntSze">
                                        {{ isset($model->name) ? $model->name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Entry Fee</th>
                                    <td data-th='entry fee' class="txtFntSze">
                                        {{ $model->entry_fee }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Minimum Users</th>
                                    <td data-th='Minimum users' class="txtFntSze">{{ $model->minimum_users }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Maximum Users</th>
                                    <td data-th='Maxmimum users' class="txtFntSze">{{ $model->maximum_users }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze"> Budget</th>
                                    <td data-th='Maxmimum users' class="txtFntSze">{{ $model->budget }}</td>
                                </tr>

                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Publish Date</th>
                                    <td data-th='publish date' class="txtFntSze">{{ $model->publish_date_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Live date</th>
                                    <td data-th='live date' class="txtFntSze">{{ $model->live_date_time }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Position Paid</th>
                                    <td data-th='paid position' class="txtFntSze">{{ $model->paid_position }}</td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">End Date</th>
                                    <td data-th='paid position' class="txtFntSze">{{ $model->end_date_time }}</td>
                                </tr>


                                {{-- <tr>
                                    <table>
                                        @foreach ($paid_position as $position)
                                        <th width="30%" class="text-right txtFntSze">{{ $model->position }}</th>
                                        <td data-th='paid position' class="txtFntSze">{{ $model->amount }}</td>
                                        @endforeach
                                    </table>
                                </tr> --}}

                                {{-- <tr>
                                    <th width="30%" class="text-right txtFntSze">Number of Games</th>
                                    <td data-th='paid position' class="txtFntSze">{{$GamesCount}}</td>
                                </tr> --}}

                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Number Of Stock</th>
                                    <td data-th='number of content' class="txtFntSze">
                                        {{ $model->number_of_stock }}
                                    </td>
                                </tr>

                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Icon</th>
                                    <td data-th='Icon' class="txtFntSze"><img height="50" width="50"
                                            src="{{ $model->image }}" /></td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Registered On</th>
                                    <td data-th='register on' class="txtFntSze">
                                        {{ date(Config::get('Reading.date_format'), strtotime($model->created_at)) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Status</th>
                                    <td style="font-weight: bolder;color:black;" data-th='Status' class="txtFntSze">
                                        <b> {{ streek_status[$model->status] }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%" class="text-right txtFntSze">Description</th>
                                    <td data-th='number of content' class="txtFntSze">{!! $model->description !!} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover brdrclr" width="100%">
                            <h2>
                                Payout Details
                            </h2>
                            <thead>
                                <tr>
                                    <th width="5%">{{ trans('Position.') }}</th>
                                    <th width="5%">{{ trans('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                <?php $number = 1; ?>
                                @if (!empty($paid_position))
                                    @foreach ($paid_position as $position)
                                        <tr class="items-inner">
                                            <td>{{ $position->position }}</td>
                                            <td>{{ $position->amount }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover brdrclr" width="100%">
                            <h2>
                                Stock Details
                            </h2>
                            <thead>
                                <tr>
                                    <th width="5%">{{ trans('Name.') }}</th>
                                    <th width="5%">{{ trans('Ticker') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                <?php $number = 1; ?>
                                @if (!empty($stockDetails))
                                    @foreach ($stockDetails as $stock)
                                        <tr class="items-inner">
                                            <td>{{ $stock->name }}</td>
                                            <td>{{ $stock->ticker }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">Question List</h3>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-6">
                        <div id="info1"></div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>{{ trans('SN.') }}</th>
                                    <th>{{ trans('Game') }}</th>
                                    <th>{{ trans('Question') }}</th>
                                    <th>{{ trans('Option 1') }}</th>
                                    <th>{{ trans('Option 2') }}</th>
                                    <th>{{ trans('Answer') }}</th>
                                    <th>{{ trans('Released answer') }}</th>
                                    <th>{{ trans('Start Date & Time') }}</th>
                                    <th>{{ trans('End Date & Time') }}</th>
                                    <th>{{ trans('Status') }}</th>
                                    <th>{{ trans('Action') }}</th>

                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                <?php $number = 1; ?>
                                @if (!empty($GamesDetails))
                                    @foreach ($GamesDetails as $GamesDetail)
                                        <tr class="items-inner">
                                            <td>{{ $number++ }}</td>
                                            <td>{{ $GamesDetail->name }}</td>
                                            <td>{{ $GamesDetail->question }}</td>
                                            <td>{{ $GamesDetail->option_one }}</td>
                                            <td>{{ $GamesDetail->option_two }}</td>
                                            @if (empty($GamesDetail->answer))
                                                <td></td>
                                            @elseif($GamesDetail->answer == 'option_one')
                                                <td>{{ 'Option 1' }}</td>
                                            @else
                                                <td>{{ 'Option 2' }}</td>
                                            @endif
                                            @if (!empty($GamesDetail->answer))
                                                <td>{{ 'Yes' }}</td>
                                            @else
                                                <td>{{ 'No' }}</td>
                                            @endif

                                            <td>{{ $GamesDetail->start_time }}</td>
                                            <td>{{ $GamesDetail->end_time }}</td>

                                            <?php $current_time = strtotime('now');
                                            $game_end_time = strtotime($GamesDetail->end_time);
                                            ?>
                                            <td data-th='' style="font-weight: bolder;color:black;">
                                                {{ Game_status[$GamesDetail->status] }}
                                            </td>


                                            <td>
                                                @if ($GamesDetail->status == 0)
                                                    <a title="Click To Release Game"
                                                        href='{{ route('Games.status', [$GamesDetail->id, 1]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-success btn-small status_any_item_game_start"><span
                                                            class="fa fa-check"></span>
                                                    </a>
                                                @endif
                                                @if ($GamesDetail->status == 1)
                                                    <button id="editGameTime"
                                                        onclick="editgametime({{ $GamesDetail->id }},'{{ $GamesDetail->start_time }}','{{ $GamesDetail->end_time }}')"
                                                        class="btn btn-info" title="Edit Time"><span
                                                            class="fa fa-edit"> </span></button>

                                                    <a title="Click To End Game"
                                                        href='{{ route('Games.status', [$GamesDetail->id, 2]) }}'
                                                        data-confirm='Are you sure?'
                                                        class="btn btn-danger btn-small status_any_item"><span
                                                            class="fa fa-check"></span>
                                                    </a>
                                                @endif
                                                @if ($GamesDetail->status == 2)
                                                    <button id="answerRelease"
                                                        onclick="answerRelease({{ $GamesDetail->id }})"
                                                        class="btn btn-success" title="Release Answer"><span
                                                            class="fa fa-ban"> </span></button>
                                                @endif
                                            </td>


                                        </tr>
                                    @endforeach
                                @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">User's List</h3>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-6">
                        <div id="info1"></div>
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%">{{ trans('SN.') }}</th>
                                    <th width="15%">{{ trans('Username') }}</th>
                                    <th width="15%">{{ trans('email') }}</th>
                                    <th width="15%">{{ trans('Status') }}</th>
                                    <th width="15%">{{ trans('Won Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody id="powerwidgets">
                                <?php $number = 1; ?>
                                @if (!empty($streek_participants))
                                    @foreach ($streek_participants as $UserDetail)
                                        <tr class="items-inner">
                                            <td>{{ $number++ }}</td>
                                            <td>{{ $UserDetail->username }}</td>
                                            <td>{{ $UserDetail->email }}</td>
                                            <td>{{ streek_participant[$UserDetail->status] }}</td>
                                            <td>{{ $UserDetail->won_amount }}</td>

                                        </tr>
                                    @endforeach
                                @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal edit game-->
        <div id="editGamemodel" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><b>Edit Time</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            {{ Form::open(['role' => 'form', 'url' => '', 'class' => 'mws-form', 'id' => 'editGameform', 'files' => true, 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group <?php echo $errors->first('start_time') ? 'has-error' : ''; ?>">
                                        <div class="mws-form-row ">
                                            {{ Form::hidden('game_id', '', ['class' => 'form-control', 'id' => 'game_id']) }}
                                            {{ Form::hidden('status', 1, ['class' => 'form-control', 'id' => 'status']) }}

                                            {!! Html::decode(Form::label('start_time', trans('Start Time') . '<span class="requireRed"></span>', ['class' => 'mws-form-label'])) !!}
                                            <div class="mws-form-item">
                                                {{ Form::text('start_time', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'id' => 'start_time_game', 'required' => 'required', 'readonly' => 'true']) }}
                                                <div class="error-message help-inline">
                                                    <?php echo $errors->first('start_time'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group <?php echo $errors->first('end_time') ? 'has-error' : ''; ?>">
                                        <div class="mws-form-row ">
                                            {!! Html::decode(Form::label('end_time', trans('End Time') . '<span class="requireRed"></span>', ['class' => 'mws-form-label'])) !!}
                                            <div class="mws-form-item">
                                                {{ Form::text('end_time', '', ['class' => 'form-control ', 'autocomplete' => 'off', 'id' => 'end_time_game', 'required' => 'required']) }}
                                                <div class="error-message help-inline">
                                                    <?php echo $errors->first('end_time'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mws-button-row">
                                <input type="button" value="{{ trans('Save') }}" onclick="storeGameTime();"
                                    class="btn btn-danger">

                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="AnswerRelease" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><b>Release Answer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            {{ Form::open(['role' => 'form', 'url' => '', 'class' => 'mws-form', 'id' => 'releaseAnswerForm', 'files' => true, 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group <?php echo $errors->first('answer') ? 'has-error' : ''; ?>">
                                        {{ Form::hidden('game_id', '', ['class' => 'form-control', 'id' => 'gameid']) }}
                                        {{ Form::hidden('streek_id', $model->id, ['class' => 'form-control', 'id' => 'streek_id']) }}

                                        <div class="mws-form-row">
                                            {!! Html::decode(Form::label('answer', trans('Release Answer') . '<span class="requireRed"> </span>', ['class' => 'mws-form-label'])) !!}
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" id="option_one" name="answer" value="option_one"
                                                    {{ $model->answer == 'option_one' ? 'checked' : '' }}> <b>Option
                                                    One</b></label>
                                            <label class="radio-inline">
                                                <input type="radio" id="option_two" name="answer" value="option_two"
                                                    {{ $model->answer == 'option_two' ? 'checked' : '' }}><b>Option
                                                    Two</b></label>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="mws-button-row">
                                <input type="button" value="{{ trans('Save') }}" onclick="storeReleaseAnswer();"
                                    class="btn btn-danger">

                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div> --}}

    </div>
    <script>
        $(function() {
            $('#start_time_game').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
            });
            $('#end_time_game').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: false
            });
            $("#start_time_game").on("dp.change", function(e) {
                $('#end_time_game').data("DateTimePicker").minDate(e.date);
            });
            $("#end_time_game").on("dp.change", function(e) {
                $('#start_time_game').data("DateTimePicker").maxDate(e.date);
            });
        });


        function editgametime(game_id, start_time, end_time) {
            $('#game_id').val(game_id);
            $('#start_time_game').val(start_time);
            $('#end_time_game').val(end_time);

            $("#editGamemodel").modal('show');
        }

        function storeGameTime() {
            var dataString = $("#editGameform").serialize();
            $.ajax({
                url: '{{ url('admin/games/change-time') }}',
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
                        location.reload();
                        $('#editGamemodel').modal('hide');
                        toastr.success('Time has been changed successfully');


                    }


                }
            });

        }



        function answerRelease(game_id) {
            $('#gameid').val(game_id);
            $("#AnswerRelease").modal('show');
        }


        function storeReleaseAnswer() {
            var dataString = $("#releaseAnswerForm").serialize();
            $.ajax({
                url: '{{ url('admin/games/release-answer') }}',
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
                        location.reload();
                        $('#AnswerRelease').modal('hide');
                        toastr.success('Time has been changed successfully');


                    }


                }
            });

        }


        $(function() {
            $(document).on('click', '.delete_any_item', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to delete this ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });

            /**
             * Function to change status
             *
             * @param null
             *
             * @return void
             */
            $(document).on('click', '.status_any_item', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to end this game ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });

            $(document).on('click', '.status_any_item_game_start', function(e) {
                e.stopImmediatePropagation();
                url = $(this).attr('href');
                bootbox.confirm("Are you sure want to realese this game ?",
                    function(result) {
                        if (result) {
                            window.location.replace(url);
                        }
                    });
                e.preventDefault();
            });
        });
    </script>
@stop
