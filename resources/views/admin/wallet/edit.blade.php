@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.userManagement.corporateadmin') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.cop-admin.update", [$corpuser->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$corpuser->id}}" >
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="name">{{ trans('cruds.userManagement.corporatecompanyname') }}*</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($corpuser) ? $corpuser->name : '') }}" required>
                        </div>
                        @if($errors->has('name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="username">{{ trans('cruds.userManagement.corporateusername') }}*</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username', isset($CorporateMeta['username']) ? $CorporateMeta['username'] : '') }}" required>
                        </div>
                        @if($errors->has('username'))
                            <em class="invalid-feedback">
                                {{ $errors->first('username') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="email">{{ trans('cruds.userManagement.email') }}*</label>
                        <input type="email" id="name" name="email" class="form-control" value="{{ old('name', isset($corpuser) ? $corpuser->email : '') }}" required>
                        </div>
                        @if($errors->has('email'))
                            <em class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="password">{{ trans('cruds.user.fields.password') }}*</label>
                        <input type="password" id="name" name="password" class="form-control" value="{{ old('name', isset($corpuser) ? $corpuser->name : '') }}" required>
                        </div>
                        @if($errors->has('password'))
                            <em class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="phone">{{ trans('cruds.userManagement.phone') }}*</label>
                        <input type="number" id="phone" name="mobile" class="form-control" value="{{ old('mobile', isset($corpuser) ? $corpuser->mobile : '') }}" required>
                        </div>
                        @if($errors->has('phone'))
                            <em class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('wallettype') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="wallettype">Select {{ trans('cruds.userManagement.wallettype') }}*</label>
                        <select name="wallettype" class="form-control">
                            <option hidden>Select</option>
                            <option value="prepaid" {{isset($CorporateMeta['wallettype']) && ($CorporateMeta['wallettype'] == 'postpaid') ? 'selected' : ''}}>Prepaid/Dabit</option>
                            <option value="postpaid" {{isset($CorporateMeta['wallettype']) && ($CorporateMeta['wallettype'] == 'prepaid') ? 'selected' : ''}}>Postpaid</option>
                        </select>
                        </div>
                        @if($errors->has('wallettype'))
                            <em class="invalid-feedback">
                                {{ $errors->first('wallettype') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6 walletamount">
                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="name">{{ trans('cruds.userManagement.walletlimit') }}*</label>
                            <input type="number" name="walletamount" value="{{ old('mobile', isset($corpuser) ? $corpuser->mobile : '') }}" class="form-control" id="walletamount">
                        </div>
                        @if($errors->has('name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-group mt-2">
                <strong><strong>Billing Cycle</strong></strong>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Bill Generate Date </label>
                    <input type="date" name="pay_date" class="form-control" value="{{ old('pay_date', isset($CorporateMeta['pay_date']) ? $CorporateMeta['pay_date'] : '') }}">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Bill Due Date</label>
                    <input type="date" name="due_date" class="form-control"  value="{{ old('due_date', isset($CorporateMeta['due_date']) ? $CorporateMeta['due_date'] : '') }}">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>


            </div>

            <div class="form-group mt-2">
                <strong>Add Gst Details</strong>
                <hr>
            </div>
            <div class="row card-gst">
                <div class="col-md-12">
                    <div class="row gst-card">
                        <div class="col-md-4">
                            <div class="row gstsub-card">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <div class="col-sm-12">
                                        <div class="grp-f">
                                            <label>Entity Name</label>
                                            <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="entityname">
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="grp-f">
                                            <label>Country</label>
                                            <select class="browser-default custom-select form-control" id="country-dropdown" name="country">
                                                <option selected>Country</option>
                                                @foreach ($Countries as $country)
                                                    <option value="{{ $country->id }}" {{!empty(request()->get('country')) && (request()->get('country') == $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="grp-f">
                                            <label>State</label>
                                            <select class="browser-default custom-select form-control" id="state-dropdown" name="state">
                                                <option selected>State</option>
                                            </select>
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="grp-f">
                                            <label>GST Number</label>
                                            <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="gstnumber">
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                                    <input type="hidden" name="del_entity_id" value="" id="del_entity_id">
                                    <div class="col-sm-12">
                                        <div class="grp-f">
                                            <label>Address</label>
                                            <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="gstaddress">
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="grp-f">

                                            <button type="button" class="btn btn-primary float-right gstsave" name="" id="">Save</button>
                                        </div>
                                        <p class="helper-block">
                                        </p>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-1">

                        </div>
                        <div class="col-md-7">
                            <table class="table my-table">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Entity Name</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">State</th>
                                    <th scope="col">Gst</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody class="append-table-data">
                                    @if(!empty($multigst))
                                    @php $p = 1 @endphp
                                        @foreach ($multigst as $gstkey => $gstval)
                                            <tr class="rowdelet">
                                            <th scope="row">{{$p}}</th>
                                            <td>{{$gstval->entity_name}}</td>
                                            <td>{{$gstval->country}}</td>
                                            <td>{{$gstval->state}}</td>
                                            <td>{{$gstval->gst_number}}</td>
                                            <td>{{$gstval->address}}</td>
                                            <td><i class="fa fa-close remove" data-id = "{{$gstval->id}}"></i></td>
                                            <td>
                                            <input type="hidden" name="entity[{{$p}}][name]" value="{{$gstval->entity_name}}">
                                            <input type="hidden" name="entity[{{$p}}][country]" value="{{$gstval->country}}">
                                            <input type="hidden" name="entity[{{$p}}][state]" value="{{$gstval->state}}">
                                            <input type="hidden" name="entity[{{$p}}][gstnumber]" value="{{$gstval->gst_number}}">
                                            <input type="hidden" name="entity[{{$p}}][gstaddress]" value="{{$gstval->address}}">
                                            <input type="hidden" name="entity[{{$p}}][prvid]" value="{{$gstval->id}}">

                                            </td>
                                            </tr>
                                            @php $p++ @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                              </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group mt-2">
                <strong>Payment Terms</strong>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="grp-f">
                        <label>Payment Terms PDF</label>
                    <input type="file" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                @if(!empty($CorporateMeta['pdf']))
                <div class="col-sm-12 mb-5">
                   <embed src= "{{ url('policy-pdf') . '/' . $CorporateMeta['pdf'] }}" width= "500" height= "375">
                </div>
                @endif



            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function ($) {
        $('.walletamount').hide();
        $('select').on('change', function() {
            if(this.value == 'postpaid'){
                $('.walletamount').show();
            }else{
                $('.walletamount').hide();
            }
        });

    });

    </script>
    <script>
     $(document).ready(function() {

    /*Country Dropdown Change Event*/
    $('#country-dropdown').on('change', function() {

        var idCountry = this.value;
        $("#state-dropdown").html('');
        $.ajax({
            url: "{{ url('admin/fetch-states') }}",
            type: "POST",
            data: {
                country_id: idCountry,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('#state-dropdown').html(
                    '<option value="">-- Select State --</option>');
                $.each(result.states, function(key, value) {
                    $("#state-dropdown").append('<option value="' + value
                        .state_id + '">' + value.state_name + '</option>');
                });
                $('#city-dropdown').html('<option value="">-- Select City --</option>');
            }
        });
    });

    /*State Dropdown Change Event*/
    $('#state-dropdown').on('change', function() {
        var idState = this.value;
        $("#city-dropdown").html('');
        $.ajax({
            url: "{{ url('admin/fetch-cities') }}",
            type: "POST",
            data: {
                state_id: idState,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(res) {
                $('#city-dropdown').html('<option value="">-- Select City --</option>');
                $.each(res.cities, function(key, value) {
                    $("#city-dropdown").append('<option value="' + value
                        .city_name + '">' + value.city_name + '</option>');
                });
            }
        });
    });

    });
    </script>
    <script>
        $(document).ready(function() {
            var deltval = $('.rowdelet').length;
            var i = deltval+1;
            $('.gstsave').click(function(){
                var entityname = $('#entityname').val();
                var country = $('#country-dropdown').val();
                var state = $('#state-dropdown').val();
                var gstnumber = $('#gstnumber').val();
                var gstaddress = $('#gstaddress').val();

                var html = '<tr class="rowdelet">'+
                            '<th scope="row">'+i+'</th>'+
                            '<td>'+entityname+'</td>'+
                            '<td>'+country+'</td>'+
                            '<td>'+state+'</td>'+
                            '<td>'+gstnumber+'</td>'+
                            '<td>'+gstaddress+'</td>'+
                            '<td><i class="fa fa-close remove"></i></td>'+
                            '<td>'+
                            '<input type="hidden" name="entity['+i+'][name]" value="'+entityname+'">'+
                            '<input type="hidden" name="entity['+i+'][country]" value="'+country+'">'+
                            '<input type="hidden" name="entity['+i+'][state]" value="'+state+'">'+
                            '<input type="hidden" name="entity['+i+'][gstnumber]" value="'+gstnumber+'">'+
                            '<input type="hidden" name="entity['+i+'][gstaddress]" value="'+gstaddress+'">'+
                            '</td>'+
                            '</tr>';
                            $(".append-table-data").each(function() {
                                i++
                                $(this).append(html);
                        });

                            $('#entityname').val('');
                            $('#country-dropdown').val('');
                            $('#state-dropdown').val('');
                            $('#gstnumber').val('');
                            $('#gstaddress').val('');


            });
            var delarry = [];
            $('.my-table').on('click','.remove',function(){
                delarry.push($(this).data('id'));
                $('#del_entity_id').val(delarry);
                $(this).parents('tr').remove();

            });
            // $('.remove').click(function(){
            //     var del = $('.rowdelet').length;
            //     alert(del)
            // });

        });
    </script>

@stop
