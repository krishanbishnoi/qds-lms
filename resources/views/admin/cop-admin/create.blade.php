@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.cop-admin.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.cop-admin.index') }}">Corporate Admin</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <form action="{{ route("admin.cop-admin.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.userManagement.corporatecompanyname') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                            <label for="username">{{ trans('cruds.userManagement.corporateusername') }}*</label>
                            <input type="text" id="username" name="username" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">{{ trans('cruds.userManagement.email') }}*</label>
                            <input type="email" id="name" name="email" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">{{ trans('cruds.user.fields.password') }}*</label>
                            <input type="password" id="name" name="password" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>

                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label for="phone">{{ trans('cruds.userManagement.phone') }}*</label>
                            <input type="number" id="phone" name="phone" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('wallettype') ? 'has-error' : '' }}">
                            <label for="wallettype">Select {{ trans('cruds.userManagement.wallettype') }}*</label>
                            <select name="wallettype" class="form-control">
                                <option hidden>Select</option>
                                <option value="prepaid">Prepaid/Debit</option>
                                <option value="postpaid">Postpaid</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-6 walletamount">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.userManagement.walletlimit') }}*</label>
                                <input type="number" name="walletamount" value="" class="form-control" id="walletamount">

                        </div>
                    </div>
                </div>
                <div class="walletamount">
                    <div class="form-group mt-2">
                        <strong><strong>Billing Cycle</strong></strong>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bill Generate Date </label>
                                <input type="date" name="pay_date" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bill Due Date</label>
                                <input type="date" name="due_date" class="form-control"  value="">
                            </div>
                        </div>


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
                                            <div class="form-group">
                                                <label>Entity Name</label>
                                                <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="entityname">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Select Country</label>
                                                <select class="form-control" id="country-dropdown" name="country">
                                                    <option selected>Country</option>
                                                    @foreach ($Countries as $country)
                                                        <option value="{{ $country->id }}" {{!empty(request()->get('country')) && (request()->get('country') == $country->id) ? 'selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Select State</label>
                                                <select class="form-control" id="state-dropdown" name="state">
                                                    <option selected>State</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>GST Number</label>
                                                <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="gstnumber">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="doc" class="form-control" value="" accept="application/pdf,application/vnd.ms-excel" id="gstaddress">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                                <button type="button" class="btn btn-primary float-right gstsave" name="" id="">Save</button>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Payment Terms PDF</label>
                            <input type="file" name="banner" class="file-upload-default" accept="application/pdf,application/vnd.ms-excel">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                              </span>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6">

                        <div class="form-group">
                            <label>Upload Aggreement</label>
                            <input type="file" name="aggreement" class="file-upload-default" accept="application/pdf,application/vnd.ms-excel">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                              </span>
                            </div>
                        </div>

                    </div>



                </div>
                <div>
                    <input class="btn btn-gradient-primary me-2" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
          </div>
        </div>
      </div>

    </div>
</div>
@endsection


@section('scripts')

<script>
    $(document).ready(function () {
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
          var i = 1;
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
          $('.my-table').on('click','.remove',function(){
              $(this).parents('tr').remove();
          });
          // $('.remove').click(function(){
          //     var del = $('.rowdelet').length;
          //     alert(del)
          // });

      });
    </script>


@stop
