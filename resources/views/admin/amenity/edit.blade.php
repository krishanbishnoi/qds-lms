@extends('layouts.admin')
@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <a class="btn btn-gradient-primary btn-fw" href="{{ route('admin.amenity.index') }}">Back</a>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.amenity.index') }}">Amenities</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>
    <div class="row">

      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <form action="{{ route("admin.amenity.update", [$Amenity->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$Amenity->id}}" >
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <div class="grp-f">
                            <label for="name">{{ trans('cruds.allhotel.fields.policy_title') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($Amenity) ? $Amenity->title : '') }}" required>
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
                    <div class="col-sm-4">
                        <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                            <div class="grp-f">
                                <label for="icon">Icon Image</label>
                                <input type="file" name="icon" class="form-control" id="icon" accept="image/png, image/gif, image/jpeg" >
                                <span class="error"> </span>
                            </div>
                            @if ($errors->has('icon'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('icon') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.address_helper') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <img src="{{url('amenity-icon').'/'.$Amenity['icon']}}" alt="" style="height:50px">
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <div class="grp-f">
                                <label for="status">Status*</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="" hidden>Select</option>
                                    <option value="1" {{($Amenity['status'] == 1) ? 'selected' : ''}}>Active</option>
                                    <option value="0" {{($Amenity['status'] == 0)? 'selected' : ''}}>Inactive</option>
                                </select>
                                <span class="error"> </span>
                            </div>
                            @if ($errors->has('status'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.company.fields.name_helper') }}
                            </p>
                        </div>
                    </div>

                </div>

                <div class="form-check {{ $errors->has('name') ? 'has-error' : '' }}">


                    <input class="form-check-input" name="common" type="checkbox" value="1" id="chkPassport" {{($Amenity->is_common == 1) ? 'checked' : ''}} />
                    <label class="form-check-label" for="chkPassport">Is Common</label>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>
                {{--
            @if($Amenity->is_common == 0)
                <div class="form-group {{ $errors->has('hotel') ? 'has-error' : '' }} hideifchecked">
                    <label for="name">{{ trans('cruds.hotel.fields.room_type') }}*</label>
                    @php
                    $type = $Amenity->room_type;
                    $explod = explode(',',$type);
                    @endphp
                    <select class="js-example-basic-multiple" name="roomtype[]" multiple="multiple">
                        @if(!empty($roomtype))
                        @foreach ($roomtype as $key => $room)
                        <option value="{{$room->id}}" {{ !empty($explod)  && in_array($room->id,$explod) ? 'selected' : ''}}>{{$room->title}}</option>
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

                <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }} hideifchecked">
                    <label for="price">{{ trans('cruds.allhotel.fields.price') }}*</label>
                    <input type="text" id="name" name="price" class="form-control" value="{{ old('price', isset($Amenity) ? $Amenity->price : '') }}" required>
                    @if($errors->has('price'))
                        <em class="invalid-feedback">
                            {{ $errors->first('price') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.company.fields.name_helper') }}
                    </p>
                </div>
            @endif
            --}}



                <div>
                    <input class="btn btn-gradient-primary me-2'" type="submit" value="{{ trans('global.save') }}">
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
    $(document).ready(function() {
        // if ($('#chkPassport').is(":checked")) {
        //     $(".hideifchecked").hide();
        // }
        // else {
        //             $(".hideifchecked").show();
        //         }
        $('.js-example-basic-multiple').select2();

        $(function () {
            $("#chkPassport").click(function () {
                if ($(this).is(":checked")) {
                    $(".hideifchecked").hide();
                } else {
                    $(".hideifchecked").show();
                }
            });
        });
    });
    </script>
@stop
