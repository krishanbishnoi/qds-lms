@extends('layouts.corporate')
@section('content')
    <div class="applyFilter d-lg-none mb-2 justify-content-between align-items-center">
        <h3 class="box-title">Showing 23 result</h3>
        <button type="button" class="hotelFilterToggle"><lottie-player src="../images/filter.json" background="transparent"
                speed="1" style="width: 40px; height: 40px;" loop="" autoplay=""></lottie-player></button>
    </div>
    <div id="filterGroupBox">
        <div class="d-flex d-lg-none justify-content-between align-items-center p-2">
            <h3>Filter</h3>
            <button class="hotelFilterClose" type="button"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="filterGroup">
            <div class="d-lg-flex gap-3 align-items-center mb-lg-4">
                <div class="filterInput destination">
                    <figure><img src="../images/destination-icon.svg" alt="" width="30" height="30"></figure>
                    <label>Destination or Hotel</label>
                    <input type="text" class="form-control" placeholder="Where to?">
                </div>
                <div class="filterInput">
                    <figure><img src="../images/checkin-icon.svg" alt="" width="30" height="30">
                    </figure>
                    <label>Check In</label>
                    <input type="text" id="checkInDate" class="form-control" value="" placeholder="--/--/----">
                </div>

                <div class="filterInput">
                    <figure><img src="../images/checkout-icon.svg" alt="" width="30" height="30">
                    </figure>
                    <label>Check Out</label>
                    <input type="text" id="checkOutDate" class="form-control" value="" placeholder="--/--/----">
                </div>

                <div class="filterInput">
                    <figure><img src="../images/guest-icon.svg" alt="" width="30" height="30">
                    </figure>
                    <label>Guests and rooms</label>
                    <div class="dropdown guestRoomDropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" aria-expanded="false">2 Guests, 1 Room</a>
                        <div class="dropdown-menu">
                            <h4>Enter no. of Guests and Rooms</h4>
                            <ul class="">
                                <li>
                                    <div class="qtyBox">
                                        <label for="">Guests</label>
                                        <div class="qty-input">
                                            <button class="qty-count qty-count--minus" data-action="minus"
                                                type="button">-</button>
                                            <input class="product-qty" type="number" name="product-qty" min="0"
                                                max="10" value="1">
                                            <button class="qty-count qty-count--add" data-action="add"
                                                type="button">+</button>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="qtyBox">
                                        <label for="">Rooms</label>
                                        <div class="qty-input">
                                            <button class="qty-count qty-count--minus" data-action="minus"
                                                type="button">-</button>
                                            <input class="product-qty" type="number" name="product-qty" min="0"
                                                max="10" value="1">
                                            <button class="qty-count qty-count--add" data-action="add"
                                                type="button">+</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>

                <div class="searchFilterButton">
                    <button type="button" class="btn btn-primary"><img src="../images/search-white-icon.svg" alt=""
                            width="18" height="18"></button>
                </div>

            </div>
            <div class="d-lg-flex gap-3 justify-content-between align-items-center  mb-4">
                <div class="priceRange d-flex align-items-center gap-2">
                    <span class="priceValue" id="range1">
                        <img src="../images/address.svg" alt=""> 0d
                    </span>
                    <div class="priceSlider">
                        <div class="slider-track"></div>
                        <input type="range" min="0" max="10000" value="1000" id="slider-1"
                            oninput="slideOne()">
                        <input type="range" min="0" max="10000" value="8000" id="slider-2"
                            oninput="slideTwo()">
                    </div>
                    <span class="priceValue" id="range2">
                        100
                    </span>
                </div>
                {{-- <div class="categoryFilter">
                    <ul>
                        <li>
                            <input type="radio" id="allFilter" name="hotelHouse" checked>
                            <label for="allFilter">All</label>
                        </li>
                        <li>
                            <input type="radio" id="hotelFilter" name="hotelHouse">
                            <label for="hotelFilter">Hotels</label>
                        </li>
                        <li>
                            <input type="radio" id="houseFilter" name="hotelHouse">
                            <label for="houseFilter">House/Apartment</label>
                        </li>
                    </ul>
                </div> --}}
                <div class="shortFilter">
                    <ul>
                        <li class="dropdown ratingGroup">
                            <a href="javascript:void(0)" class="dropdownToggle dropdown-toggle" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"><img src="../images/ratings-icon.svg" alt=""
                                    width="12" height="12"> Ratings</a>
                            <div class="dropdown-menu">
                                <h4>View hotels by rating</h4>
                                <h5>Star Rating</h5>
                                <ul class="">
                                    <li>
                                        <div class="cstmCheckbox">
                                            <input type="checkbox" id="check1">
                                            <label for="check1">2 Star <span>(22 result)</span></label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="cstmCheckbox">
                                            <input type="checkbox" id="check2">
                                            <label for="check2">3 Star <span>(46 result)</span></label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="cstmCheckbox">
                                            <input type="checkbox" id="check3">
                                            <label for="check3">4 Star <span> (82 result)</span></label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="cstmCheckbox">
                                            <input type="checkbox" id="check4">
                                            <label for="check4">5 Star <span>(65 result)</span></label>
                                        </div>

                                    </li>
                                </ul>

                            </div>
                        </li>
                        <li class="dropdown shortingGroup">
                            <a href="javascript:void(0)" class="dropdownToggle dropdown-toggle" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"><img src="../images/short-icon.svg" alt=""
                                    width="12" height="12">
                                Short</a>
                            <div class="dropdown-menu">
                                <ul class="">
                                    <li>
                                        <a href="">Price low to high</a>
                                    </li>
                                    <li>
                                        <a href="">Price high to low</a>
                                    </li>
                                    <li>
                                        <a href="">Popularity</a>
                                    </li>
                                    <li>
                                        <a href="">Availability</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="dropdown amenitiesFilter">
                            <a href="javascript:void(0)" class="dropdownToggle dropdown-toggle" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"><img src="../images/filters-icon.svg" alt=""
                                    width="12" height="12"> Filter</a>
                            <div class="dropdown-menu">
                                <h4 class="mb-0">Amenities</h4>
                                <ul class="">
                                    @foreach ($allAmenities as $filAmenities)                                    
                                    <li>
                                        <div class="cstmCheckbox">
                                            <input type="checkbox" id="amenitiesCheck_{{$filAmenities['id']}}" value="{{$filAmenities['id']}}">
                                            <label for="amenitiesCheck_{{$filAmenities['id']}}">{{$filAmenities['title']}}</label>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>

                                <div class="text-end p-3 border-top">
                                    <button class="btn btn-primary">Apply</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="hotelResult">
                @if ($hotelList)
                <h3 class="box-title mb-3 d-none d-lg-block">Showing {{ count($hotelList) }} result</h3>
                <ul class="hotelList nav nav-tabs" id="myTab">
                    @foreach ($hotelList as $hotel)
                    <li class="nav-item hotelTextbox" data-hotel-id="{{ $hotel['id'] }}"
                    onclick="getBookabelHotel('{{ $hotel['id'] }}')">
                        <div class="boxOuter">
                            <figure>
                                @if ($hotel['primary_image'])
                                <img src="{{ url('hotel/banner') . '/' . $hotel['primary_image'] }}"
                                    alt="">
                                @elseif (isset($hotel['hotel_image']))
                                    <img src="{{ $hotel['hotel_image'] }}" alt="">
                                @else
                                    <img src="/hotel/banner/1692166141primary_img.jpg" alt="">
                                @endif
                                <span class="HotelRating">
                                    <i class="bi bi-star-fill"></i> 5
                                </span>
                            </figure>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h4>{{ ucfirst($hotel['title']) }}</h4>

                            </div>
                            <div class="locationText mb-2"><img src="../images/pin.svg" alt="" width="12"
                                    class="me-2">{{ $hotel['address_1'] . ', ' . $hotel['address_2'] . ', ' . $hotel['city'] }}
                            </div>
                            <div class="d-flex justify-content-between">
                                @if($hotel['rating'])
                                <div class="reviewsCount">
                                    <i>{{ $hotel['rating'] }}</i>
                                    <span>200 <b>Reviews</b></span>
                                </div>
                                @endif
                                @if ($hotel['rooms'])
                                <div class="hotelPrice">
                                    <small>From</small>
                                    ₹ {{ $hotel['rooms'][0]->s_price ?? $hotel['rooms'][0]->r_price }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                    <span>No Hotels Found.</span>
                @endif
            </div>
        </div>
        <div class="col-lg-6 mt-4 mb-lg-0">
            <div class="tab-content BookableHotelInfo">
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end filterSidebar" tabindex="-1" id="filtercanvas"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form>
                <div class="search-main">
                    <div class="form-group filter_search mb-1">
                        <input type="text" class="form-control"
                            placeholder="Filter by Hotel name, City, State, Flight">
                    </div>
                    <div class="p-3">
                        <div class="form-group mb-3">
                            <label>I'm searching for</label>
                            <ul class="searchingList">
                                <li class="selected"><img src="../images/location-icon-white.svg" alt=""
                                        width="18" height="18">
                                    jaipur <button type="button"><img src="../images/close-white.svg" alt=""
                                            width="16"></button></li>
                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/hotel-icon.svg" alt="" width="18" height="18">
                                        Hotel</a></li>
                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/location-icon1.svg" alt="" width="18"
                                            height="18"> State</a>
                                </li>

                                <li><a class="searchToggle" href="javascript:void(0);"><img
                                            src="../images/flight-icon.svg" alt="" width="18"
                                            height="18"> Fight</a>
                                </li>
                            </ul>
                        </div>
                        <div class="form-group mb-3">
                            <label>Date Range and Time</label>
                            <div class="d-sm-flex gap-3">
                                <input type="text" id="datepicker" class="dateRange" placeholder="20 June - 30 June">
                                <input type="text" id="timepicker" class="timeRange" placeholder="02:00 - 22:00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Recent searches</label>
                            <ul class="recentSearchList">
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Rajasthan<span>India</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Jaipur<span>Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Rajasthan<span>India</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Jaipur<span>Rajasthan</span></strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="recentSearchTxt">
                                        <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                                height="32">
                                        </figure>
                                        <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="searchFilter pt-0" style="display: none;">
                    <div class="form-group filter_search mb-4">
                        <span>City <button type="button" class="categoryToggle"><img src="../images/close-black.svg"
                                    alt=""></button></span>
                        <input type="text" class="form-control" placeholder="Search City">
                    </div>
                    <ul class="recentSearchList">
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Rajasthan<span>India</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Jaipur<span>Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/hotel-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon1.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Rajasthan<span>India</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/location-icon2.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Jaipur<span>Rajasthan</span></strong>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="recentSearchTxt">
                                <figure><img src="../images/flight-icon.svg" alt="" width="32"
                                        height="32">
                                </figure>
                                <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="formBtn">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="button" class="btn btn-primary">Appy</button>
                </div>
            </form>
        </div>

    </div>
    <div class="menu-overly"></div>
@endsection
@section('scripts')
    @parent
    <script>
        jQuery(document).ready(function() {
            jQuery(document).on('click', 'li.hotelTextbox', function() {
                jQuery('li.hotelTextbox').removeClass('active');
                jQuery(this).addClass('active');
            });
            jQuery('li.hotelTextbox:nth-child(1)').trigger('click');
        });

        function getBookabelHotel(id) {
            if (id) {
                jQuery.ajax({
                    dataType: 'json',
                    method: 'POST',
                    url: '{{ route('bookablehotelinfo') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(response) {
                        var hotel_info = response.data.hotel_info;
                        var roomOptions = hotel_info.rooms.map(items => items.room_type.toUpperCase());
                        roomOptions = roomOptions ? roomOptions.join(", ") : '';
                        // console.log(hotel_info);
                        var amenities = '';
                        var roomsHtml = '';
                        
                        var hotelPrice = 0;

                        if(hotel_info.rooms.length){
                            hotel_info.rooms.forEach(function(room, key) {
                                var offerPriceHtml = '';
                                roomPrice = room.s_price??room.r_price;
                                if( hotelPrice == 0 || hotelPrice < roomPrice){
                                    hotelPrice = roomPrice;
                                }
                                if(room.s_price &&  room.r_price && room.s_price < room.r_price){
                                    var offerPrice = Math.round(Number(room.r_price)-Number(room.s_price));
                                    offerPriceHtml +=   '<span class="d-block text-end text-danger">'+
                                                            '<del>INR '+Math.round(room.r_price)+'</del> '+ Math.round((Number(offerPrice)/room.r_price)*100) +'% off'+
                                                        '</span>'+
                                                        '<span class="d-block text-end inrTxt">INR '+offerPrice+'</span>';
                                                        
                                }else{
                                    offerPriceHtml +=   '<span class="d-block text-end inrTxt">INR '+Math.round(room.r_price)+'</span>';
                                }
                                
                                roomsHtml +=    '<tr>' + 
                                                    '<td>' + 
                                                        '<strong class="d-block">'+room.room_type.toUpperCase()+'</strong>' + 
                                                        '<span class="d-block"><img src="../images/bed-icon.svg" alt="" width="18" class="me-2">'+room.bed_type.toUpperCase()+'</span>' + 
                                                        // '<span class="d-block text-success">Breakfast included</span>' + 
                                                    '</td>' + 
                                                    '<td>' + 
                                                        '<span class="d-block text-end text-gray"> 1 night, '+room.occupancy+' adults</span>'+offerPriceHtml+ 
                                                    '</td>' + 
                                                '</tr>';

                                if (room.amenity.split(',')) {
                                    room.amenity.split(',').forEach(function(amint) {
                                        if (!amenities.includes(amint)) {
                                            amenities +=
                                                '<li><img src="../images/wifi-icon.svg" alt="">' +
                                                amint.toUpperCase() + '</li>';
                                        }
                                    });
                                }
                            });
                        }else{
                            roomsHtml += '<tr><td>No rooms available.</td></tr>';
                        }

                        // console.log(roomsHtml);

                        var hotelGallery = '';
                        hotel_info.media.forEach(function(image, key) {
                            hotelGallery +=
                                '<li><a href="../images/hotel-thumb1.png" data-fancybox="gallery"><img src="' +
                                image.original_url.replace("http://localhost/", "/") +
                                '" alt=""></a></li>';
                        });

                        var hotelRating = '';
                        if (hotel_info.rating) {
                            hotelRating += '<div class="reviewsCount">' + 
                                                '<img src="../images/thumbs-up.svg" alt="">' + 
                                                '<span>'+hotel_info.rating+' <b class="d-inline-block">Rating</b></span>' + 
                                            '</div>';
                        }
                        var bookHotelRoute = '/book-hotel/'+hotel_info.id;
                        var description = (hotel_info.description.length > 100)? hotel_info.description.substr(0,100)+'<a href="'+bookHotelRoute+'?tr=d"> Read more</a>':hotel_info.description;
                        var hotelImage = hotel_info.primary_image ?? "1692166141primary_img.jpg";
                        var html =  ''+
                        '<div class="tab-pane active" id="'+hotel_info.id+'">' + 
                        '    <div class="hotelDetail">' + 
                        '        <div class="row">' + 
                        '            <div class="col-md-5">' + 
                        '                <figure>' + 
                        '                    <img src="/hotel/banner/'+hotelImage+'" alt="">' + 
                        '                </figure>' + 
                        '            </div>' + 
                        '            <div class="col-md-7 ps-md-0 d-flex flex-column">' + 
                        '                <div class="row mb-2">' + 
                        '                    <div class="col-6">' + 
                        '                        <span class="HotelRating">' + 
                        '                            <i class="bi bi-star-fill"></i> 5' + 
                        '                        </span>' + 
                        '                    </div>' + 
                        '                    <div class="col-6 text-end">' + 
                        '                        <a href="'+bookHotelRoute+'" class="btn btn-primary">Book Now</a>' + 
                        '                    </div>' + 
                        '                </div>' + 
                        '' + 
                        '                <h3 class="mb-1">'+hotel_info.title.toUpperCase()+'</h3>' + 
                        '                <div class="locationText mb-1"><img src="../images/pin.svg" alt="" width="12" class="me-2">'+
                                            hotel_info.address_1+', '+hotel_info.address_2+', '+hotel_info.city +
                        '                </div>' + 
                        '                <div class="d-flex justify-content-between mb-2">' + 
                                                hotelRating+ 
                        '                    <div class="hotelPrice">' + 
                        '                        <small>From</small>' + 
                        '                        ₹ '+hotelPrice + 
                        '                    </div>' + 
                        '                </div>' + 
                        '                <!-- Nav tabs -->' + 
                        '                <ul class="nav nav-tabs mt-auto w-100" id="myTab">' + 
                        '                    <li>' + 
                        '                        <button class="active" data-bs-toggle="tab" data-bs-target="#overviewTab1" type="button"' + 
                        '                            role="tab">Overview</button>' + 
                        '                    </li>' + 
                        '                    <li>' + 
                        '                        <button data-bs-toggle="tab" data-bs-target="#pricingTab1" type="button">Pricing</button>' + 
                        '                    </li>' + 
                        '                    <li>' + 
                        '                        <button data-bs-toggle="tab" data-bs-target="#photosTab1" type="button">Photos</button>' + 
                        '                    </li>' + 
                        '                    <li>' + 
                        '                        <button data-bs-toggle="tab" data-bs-target="#amenitiesTab1" type="button">Amenities</button>' + 
                        '                    </li>' + 
                        '                </ul>' + 
                        '' + 
                        '                <!-- Tab panes -->' + 
                        '            </div>' + 
                        '        </div>' + 
                        '' + 
                        '        <div class="tab-content">' + 
                        '            <div class="tab-pane active" id="overviewTab1">' + 
                        '                <div class="hotelContent">' + 
                        '                    <strong>Overview</strong>' + 
                        '                    <p>'+description+'</p>' + 
                        '                    <strong>Key amenities</strong>' + 
                        '                    <ul class="amenitiesList">' + 
                                                amenities+
                        '                    </ul>' + 
                        '                </div>' + 
                        '            </div>' + 
                        '            <div class="tab-pane" id="pricingTab1">' + 
                        '                <div class="hotelContent">' + 
                        '                    <strong>Room options(Single room price)</strong>' + 
                        '                    <div class="table-responsive roomOptionTable">' + 
                        '                        <table class="table">' + 
                                                    roomsHtml+
                        '                        </table>' + 
                        '                    </div>' + 
                        '                </div>' + 
                        '            </div>' + 
                        '            <div class="tab-pane" id="photosTab1">' + 
                        '                <div class="hotelContent">' + 
                        '                    <ul class="photosList">' + 
                                                   hotelGallery + 
                        '                    </ul>' + 
                        '                </div>' + 
                        '            </div>' + 
                        '            <div class="tab-pane" id="amenitiesTab1">' + 
                        '                <div class="hotelContent">' + 
                        '                    <strong>Key amenities</strong>' + 
                        '                    <ul class="amenitiesList">' + 
                                                amenities+
                        '                    </ul>' + 
                        '                </div>' + 
                        '            </div>' + 
                        '        </div>' + 
                        '' + 
                        '    </div>' + 
                        '' + 
                        '</div>' + 
                        '';
                        jQuery('.BookableHotelInfo').html(html);
                    }
                });
            }
        }
    </script>
@endsection
