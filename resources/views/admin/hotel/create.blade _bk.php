@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header heading-title">
        {{ trans('global.create') }} {{ trans('cruds.hotel.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.hotels.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <div class="grp-f">
                <label for="name">{{ trans('cruds.company.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
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
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <div class="grp-f">
                <label for="description">{{ trans('cruds.hotel.fields.description') }}*</label>
                <textarea class="form-control" class="form-control" name="description" value=""></textarea>
                </div>
                @if($errors->has('description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.name_helper') }}
                </p>
            </div>
            {{--<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <div class="grp-f">
                <label for="content">{{ trans('cruds.hotel.fields.content') }}*</label>
                <textarea class="myCkeditor form-control" class="form-control" name="content" value=""></textarea>
                </div>
                @if($errors->has('content'))
                    <em class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.name_helper') }}
                </p>
            </div>--}}
            {{--<div class="form-group {{ $errors->has('video_url') ? 'has-error' : '' }}">
                <div class="grp-f">
                <label for="video_url">{{ trans('cruds.hotel.fields.video_link') }}*</label>
                <input type="text" id="name" name="video_url" class="form-control" value="{{ old('name', isset($company) ? $company->name : '') }}" required>
                </div>
                @if($errors->has('video_url'))
                    <em class="invalid-feedback">
                        {{ $errors->first('video_url') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.name_helper') }}
                </p>
            </div>--}}
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="contact">{{ trans('cruds.hotel.fields.contact') }}</label>
                        <input type="number"  id="address" name="contact" class="form-control" >
                        </div>
                        @if($errors->has('contact'))
                            <em class="invalid-feedback">
                                {{ $errors->first('contact') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="email">{{ trans('cruds.hotel.fields.email') }}</label>
                        <input type="email"  id="address" name="email" class="form-control" >
                        </div>
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                   <div class="form-group {{ $errors->has('banner_image') ? 'has-error' : '' }}">
                       <div class="grp-f">
                       <label for="banner_image">Primary Image</label>
                       <input type="file" name="banner_image" class="form-control">
                       </div>
                       @if($errors->has('banner_image'))
                           <em class="invalid-feedback">
                               {{ $errors->first('banner_image') }}
                           </em>
                       @endif
                       <p class="helper-block">
                           {{ trans('cruds.company.fields.address_helper') }}
                       </p>
                   </div>
                </div>
                {{--<div class="col-md-6">
                   <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                       <div class="grp-f">
                       <label for="feature">{{ trans('cruds.hotel.fields.feature_image') }}</label>
                       <input type="file" name="feature_image" class="form-control">
                       </div>
                       @if($errors->has('feature'))
                           <em class="invalid-feedback">
                               {{ $errors->first('feature') }}
                           </em>
                       @endif
                       <p class="helper-block">
                           {{ trans('cruds.company.fields.address_helper') }}
                       </p>
                   </div>
                </div>--}}
            </div>
            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">
                <label for="logo">{{ trans('cruds.hotel.fields.gallery') }}</label>
                <div class="needsclick dropzone" id="logo-dropzone">

                </div>
                @if($errors->has('logo'))
                    <em class="invalid-feedback">
                        {{ $errors->first('logo') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.logo_helper') }}
                </p>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="address">{{ trans('cruds.hotel.fields.address') }}</label>
                        <textarea  id="address" name="address" class="form-control" ></textarea>
                        </div>
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                        <div class="grp-f">
                        <label for="address">{{ trans('cruds.hotel.fields.address1') }}</label>
                        <textarea  id="address" name="address" class="form-control" ></textarea>
                        </div>
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="country">Select {{ trans('cruds.hotel.fields.country') }}</label>
                        <select class="form-control" name="country" id="country-dropdown">
                            @if(!empty($Countries))
                            <option hidden value="">Select</option>
                                @foreach ($Countries as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        </div>
                        @if($errors->has('country'))
                            <em class="invalid-feedback">
                                {{ $errors->first('country') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="state">Select {{ trans('cruds.hotel.fields.state') }}</label>
                        <select class="form-control" name="state" id="state-dropdown">

                        </select>
                        </div>
                        @if($errors->has('state'))
                            <em class="invalid-feedback">
                                {{ $errors->first('state') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="city">Select {{ trans('cruds.hotel.fields.city') }}</label>
                        <select class="form-control" name="city" id="city-dropdown">

                        </select>
                        </div>
                        @if($errors->has('city'))
                            <em class="invalid-feedback">
                                {{ $errors->first('city') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="pincode">Pincode</label>
                        <input type="number" class="form-control" name="pincode" value="">
                        </div>
                        @if($errors->has('pincode'))
                            <em class="invalid-feedback">
                                {{ $errors->first('pincode') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="rooms">Total Number of Rooms</label>
                        <input type="number"  id="rooms" name="rooms" class="form-control" >
                        </div>
                        @if($errors->has('rooms'))
                            <em class="invalid-feedback">
                                {{ $errors->first('rooms') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group ">
                        <div class="grp-f">
                        <label for="available_rooms">Available Rooms</label>
                        <input type="number"  id="available_rooms" name="available_rooms" class="form-control" >
                        </div>
                        @if($errors->has('available_rooms'))
                            <em class="invalid-feedback">
                                {{ $errors->first('available_rooms') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group ">
                        <label for="address">{{ trans('cruds.hotel.fields.room_type') }}</label>
                        <select class="js-example-basic-multiple" name="roomtype[]" multiple="multiple">
                            @if(!empty($roomtype))
                            @foreach ($roomtype as $key => $room)
                            <option value="{{$room->id}}">{{$room->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group ">
                        <label for="price">{{ trans('cruds.hotel.fields.price') }}</label>
                        <input type="text"  id="address" name="price" class="form-control" >
                        @if($errors->has('price'))
                            <em class="invalid-feedback">
                                {{ $errors->first('price') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.address_helper') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <strong>Amenities</strong>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="amenity"> Select Common Amenities*</label>
                        <select class="js-example-basic-multiple" name="amenity[]" multiple="multiple">
                            @if(!empty($commonAmenity))
                            @foreach ($commonAmenity as $key => $common)
                            <option value="{{$common->id}}">{{$common->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        @if($errors->has('amenity'))
                            <em class="invalid-feedback">
                                {{ $errors->first('amenity') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>
                {{--<div class="col-sm-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="roomtype"> Select Room Type*</label>
                        <select class="js-example-basic-multiple" name="roomtype[]" multiple="multiple">
                            @if(!empty($roomtype))
                            @foreach ($roomtype as $key => $room)
                            <option value="{{$room->id}}">{{$room->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        @if($errors->has('roomtype'))
                            <em class="invalid-feedback">
                                {{ $errors->first('roomtype') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>--}}
                {{--<div class="col-sm-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="name"> Select Bed Type*</label>
                        <select class="js-example-basic-multiple" name="roomtype[]" multiple="multiple">
                            @if(!empty($roomtype))
                            @foreach ($roomtype as $key => $room)
                            <option value="{{$room->id}}">{{$room->title}}</option>
                            @endforeach
                            @endif
                        </select>
                        @if($errors->has('name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.company.fields.name_helper') }}
                        </p>
                    </div>
                </div>--}}

            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <div class="grp-f">
                <label for="amenity">Status*</label>
                <select class="form-control" name="status" >
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                </div>
                @if($errors->has('amenity'))
                    <em class="invalid-feedback">
                        {{ $errors->first('amenity') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group mt-2">
                <strong>Check in/out time</strong>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Time for check in</label>
                    <input type="time" name="check_in" class="form-control" value="">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Time for check Out</label>
                    <input type="time" name="check_out" class="form-control" value="">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Minimum advance reservations</label>
                    <input type="Number" name="p_title" class="form-control" value="">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                {{--<div class="col-sm-6">
                    <label>Minimum day stay requirements</label>
                    <input type="text" name="p_title" class="form-control">
                    <p class="helper-block">
                    </p>
                </div>--}}

            </div>
            <div class="form-group mt-2">
                <strong>SEO</strong>
                <hr>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Meta Keyword</label>
                    <input type="text" name="meta_keyword" class="form-control" value="">
                    </div>
                    <p class="helper-block">
                    </p>
                </div>
                <div class="col-sm-6">
                    <div class="grp-f">
                    <label>Meta Description</label>
                    <textarea type="text" name="meta_description" class="form-control" value=""> </textarea>
                    </div>
                    <p class="helper-block">
                    </p>
                </div>

            </div>
            <div class="form-check {{ $errors->has('is_featured') ? 'has-error' : '' }}">


                <input class="form-check-input" name="is_featured" type="checkbox" value="1" id="chkPassport" />
                <label class="form-check-label" for="is_featured">Is Featured</label>
                @if($errors->has('is_featured'))
                    <em class="invalid-feedback">
                        {{ $errors->first('is_featured') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.company.fields.name_helper') }}
                </p>
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
    Dropzone.options.logoDropzone = {
    url: '{{ route('admin.companies.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="logo"]').remove()
      $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($company) && $company->logo)
      var file = {!! json_encode($company->logo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script type="text/javascript">
    $(document).ready(function ($) {
    //     $('.myCkeditor').ckeditor( {
    //     toolbar: ["undo", "redo", "bold", "italic", "blockQuote", "ckfinder", "imageTextAlternative", "imageUpload", "heading", "imageStyle:full", "imageStyle:side", "link", "numberedList", "bulletedList", "mediaEmbed", "insertTable", "tableColumn", "tableRow", "mergeTableCells"]
    // });
    tinymce.init({ selector:'.myCkeditor' });
    });
</script>

<script>
    $(document).ready(function () {

        /*------------------------------------------
        --------------------------------------------
        Country Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#country-dropdown').on('change', function () {

            var idCountry = this.value;
            $("#state-dropdown").html('');
            $.ajax({
                url: "{{url('admin/fetch-states')}}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#state-dropdown').html('<option value="">-- Select State --</option>');
                    $.each(result.states, function (key, value) {
                        $("#state-dropdown").append('<option value="' + value
                            .state_id + '">' + value.state_name + '</option>');
                    });
                    $('#city-dropdown').html('<option value="">-- Select City --</option>');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        State Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#state-dropdown').on('change', function () {
            var idState = this.value;
            $("#city-dropdown").html('');
            $.ajax({
                url: "{{url('admin/fetch-cities')}}",
                type: "POST",
                data: {
                    state_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#city-dropdown').html('<option value="">-- Select City --</option>');
                    $.each(res.cities, function (key, value) {
                        $("#city-dropdown").append('<option value="' + value
                            .city_id + '">' + value.city_name + '</option>');
                    });
                }
            });
        });

    });
</script>
@stop
