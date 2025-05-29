@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header heading-title">
            {{-- {{ trans('global.create') }} {{ trans('cruds.hotel.title_singular') }} --}}
            Create Booking
        </div>

        <div class="card-body">
            <form id="createBookingForm" action="{{ route('admin.booking-manager.store') }}" method="POST" enctype="multipart/form-data"
                class="form-padding">
                @csrf
                <div class="first-step">
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <b>Booking Details</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="corporateAdmin">Corporate Admin*</label>
                                    <select class="form-control" name="corporateAdmin" id="corporateAdmin">
                                        <option value="">Select a corporate admin*</option>
                                    @if (!empty($copAdmin))
                                    @foreach ($copAdmin as $key => $copAdmin)
                                    <option value="{{ $copAdmin['id'] }}">
                                        {{ $copAdmin['name'] }}
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('class'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('class') }}
                                    </em>
                                    @endif
                                    <span class="error"> </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="grp-f">
                                    <label for="contact">Number of rooms*</label>
                                    <input type="number" id="numberOfRooms" name="numberOfRooms" class="form-control">
                                    <span class="error"> </span>
                                </div>
                                @if ($errors->has('contact'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('numberOfRooms') }}
                                    </em>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <b>Employees Detials</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label for="numberOfEmployee">Number of employees*</label>
                                <input type="number" name="numberOfEmployee" class="form-control" value="" id="numberOfEmployee">
                                <span class="error"> </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label for="nameOfEmployee">Name of employees*</label>
                                <input type="text" name="nameOfEmployee" placeholder="John smith, Matheu, etc.." class="form-control" value="" id="nameOfEmployee">
                                <span class="error"> </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <b>Check In/Out</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row checkINOut">
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Date/Time for check in*</label>
                                <input type="datetime-local" name="check_in" class="form-control" value="" id="check_in">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <div class="grp-f">
                                <label>Time for check Out*</label>
                                <input type="datetime-local" name="check_out" class="form-control" value="" id="check_out">
                                <span class="error"> </span>
                            </div>
                            <p class="helper-block">
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <b>Hotel/Room Details</b>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <div class="grp-f">
                                    <label for="hotel">Hotel*</label>
                                    <select class="form-control" name="hotel" id="hotel">
                                        <option value="">Select a hotel</option>
                                    @if (!empty($hotelDb))
                                    @foreach ($hotelDb as $key => $hotel)
                                    <option value="{{ $hotel['id'] }}">
                                        {{ $hotel['title'] }}
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('class'))
                                    <em class="invalid-feedback">
                                        {{ $errors->first('class') }}
                                    </em>
                                    @endif
                                    <span class="error"> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 roomsDetails">
                        <div class="roomList"></div>
                        <div class="loadingGif">
                            <img src="/images/loadingGif.gif">
                        </div>
                        <input type="hidden" name="roomType" id="roomType" value="">
                    </div>
                    <div class="mt-5">
                        <input class="btn btn-danger float-right" type="submit" value="{{ trans('global.save') }}"
                            id="save-booking">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        
        jQuery(document).on('change','input#check_in',function(e){
            jQuery("input#check_out").attr('min',jQuery(this).val());
        });

        jQuery(document).on('submit','#createBookingForm',function(e){

            e.preventDefault();
            var isStepFirstError = false;
            var stepFirstscrollTo = false;
            jQuery('#createBookingForm input, #createBookingForm select').each(function() {
                if ((!jQuery(this).val())) {
                    isStepFirstError = true;
                    jQuery(this).closest('div').find('.error').html(
                        'This field is required.');
                    if (!stepFirstscrollTo) {
                        stepFirstscrollTo = true;
                        document.querySelector('body main #' + jQuery(this).attr(
                            'id'))?.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                } else {
                    jQuery(this).closest('div').find('.error').html('');
                }
            });

            var check_in = new Date(jQuery('input#check_in').val());
            var check_out = new Date(jQuery('input#check_out').val());
            if(check_in > check_out){
                isStepFirstError = true;
                jQuery('.checkINOut').find('.error').html(
                        'Please select valid date.');
            }

            var roomType = jQuery('input#roomType').val();
            if(!roomType){
                jQuery("select#hotel").closest('div').find('.error').html('Please select a room type.');
            }else{
                
                var allowMembers = Number(jQuery('.room-card[data-room-id="'+roomType+'"] input#roomOccu').val())*jQuery('input#numberOfRooms').val();
                var selectedMember = jQuery("input#numberOfEmployee").val();
                console.log(allowMembers,selectedMember);
                if(allowMembers < selectedMember){
                    isStepFirstError = true;
                    jQuery("select#hotel").closest('div').find('.error').html('Room Member\'s limit exhausted.');
                }
            }
            if(isStepFirstError){
                return false;
            }

            var formData = new FormData(this);
            if (formData) {
                jQuery.ajax({
                    dataType: 'json',
                    method: 'POST',
                    url: '{{ route('admin.booking-manager.store') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response.message);
                        window.location.replace('/admin/booking-manager/');
                    }
                });
            }

        });

        jQuery(document).on('click','.room-card',function(){
            var roomId = jQuery(this).data('room-id');
            jQuery('.room-card').removeClass('selected');
            if(roomId){
                jQuery(this).addClass('selected');
                jQuery('input#roomType').val(roomId);
            }else{
                jQuery('input#roomType').val('');
            }
        });

        $('select#hotel').on('change',function(e){
            jQuery('.loadingGif').show();
            e.preventDefault();
            var hotelID = jQuery(this).val();
            if(hotelID){
                jQuery.ajax({
                    dataType:'json',
                    method:'POST',
                    url:'/admin/hotels/getrooms',
                    data:{_token:'{{ csrf_token() }}',hotelID:hotelID},
                    success:function(response){
                        jQuery('.loadingGif').hide();
                        var roomList = '';
                        if(response.rooms){
                            jQuery(response.rooms).each(function(key,room){
                                console.log(room);
                                var amenity = '';
                                jQuery(room.amenity?.split(','))?.each(function(key,amt){
                                    amenity +=   '<div class="amtInfo">'+
                                                    '<div class="amtIcon">'+
                                                        '<img src="/images/wifi-amt.png">'+
                                                    '</div>'+
                                                    '<div class="amtTitle">'+
                                                        '<span>'+amt+'</span>'+
                                                    '</div>'+
                                                '</div>';
                                });

                                var bedType = '';
                                var u = 1;
                                jQuery(room.bed_type?.split(','))?.each(function(key,bdtype){
                                    bedType +=   '<div class="bedInfo">'+
                                                    '<div class="bedIcon">'+u+'.'+
                                                        // '<img src="/images/wifi-amt.png">'+
                                                    '</div>'+
                                                    '<div class="bedTitle">'+
                                                        '<span>'+bdtype.replace('-',' ')+'</span>'+
                                                    '</div>'+
                                                '</div>';
                                                u++;
                                });
                                var rPriceHtml = '₹'+room.r_price;
                                var discountHtml = '';
                                var spricegHtml = '';
                                if(Number(room.r_price) > Number(room.s_price) && room.s_price){
                                    rPriceHtml = '<i></in><del>₹'+room.r_price+'</del></i>';
                                    discountAmount = '₹'+Number(room.r_price)-Number(room.s_price);
                                    discountHtml = Math.round(((Number(room.r_price)-Number(room.s_price))/room.r_price)*100)+"% off";
                                    spricegHtml = '₹'+room.s_price;
                                }
                                // console.log(amt);
                                roomList += '<div class="room-card mt-5" data-room-id="'+room.id+'">'+
                                                '<div class="roomInner">'+
                                                    '<div class="roomInfo">'+
                                                        '<input type="hidden" id="roomOccu" name="roomOccu" value="'+room.occupancy+'" />'+
                                                        '<img src="/images/person.webp">'+
                                                        '<span>x'+room.occupancy+'</span>'+
                                                    '</div>'+
                                                    '<div class="roomType">'+
                                                        'Room Type : <strong>'+room.room_type+'</strong>'+
                                                    '</div>'+
                                                    '<div class="amenities mt-3">'+
                                                        '<h6>Amenities :</h6>'+
                                                        '<div class="amtlist">'+amenity+'</div>'+
                                                    '</div>'+
                                                    '<div class="bedType mt-3">'+
                                                        '<h6>Bed Type :</h6>'+
                                                        '<div class="bedlist">'+bedType+'</div>'+
                                                    '</div>'+
                                                    '<div class="priceSection mt-3">'+
                                                        '<div class="price">'+
                                                            '<div>Price :</div>'+
                                                            '<div class="sPrice">'+spricegHtml+'</div>'+
                                                            '<div class="rPrice">'+rPriceHtml+'</div>'+
                                                            '<div class="discount">'+discountHtml+'</div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>';
                            });
                            jQuery('.roomList').html(roomList);
                        }else{
                            jQuery('.roomList').html("<i>No room's available</i>");
                        }                        
                    }
                });
            }else{
                jQuery('.loadingGif').hide();
                jQuery('.roomList').html("<i>No room's available</i>");
            }
        });
    });
</script>
@stop
