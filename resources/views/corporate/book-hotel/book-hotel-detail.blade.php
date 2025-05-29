@extends('layouts.corporate')
@section('content')
    <div class="hotelDetailPanel">
        <form id="bookingDetailsInfo">
            @csrf
            <input type="hidden" name="hotelId" value="{{ $HotelInfo['id'] }}" />
            <div class="d-sm-flex align-items-end justify-content-between mb-3">
                <div class=" mb-3 mb-md-0">
                    <h2>{{ Str::ucfirst($HotelInfo['title']) }}</h2>
                    <div class="reviewsCount">
                        @if($HotelInfo['rating'])
                        <span><img src="../images/thumbs-up.svg" alt=""> {{ $HotelInfo['rating'] }} <b class="d-inline-block">Rating</b></span>
                        @endif

                        <span>{{ $HotelInfo['address_1'].', '.$HotelInfo['address_2'].', '.$HotelInfo['city'] }}</span>

                    </div>
                </div>
                <div class="text-end">
                    <a href="javascript:void(0)" class="btn btn-outline-primary">View all Photos</a>
                </div>
            </div>
            <div class="hotelGallery">
                <div class="row">
                    <div class="col-md-6 pe-md-2 pb-3">
                        <figure class="bigImg">
                            @if ($HotelInfo['primary_image'])
                            <img src="{{ url('hotel/banner') . '/' . $HotelInfo['primary_image'] }}"
                                alt="">
                            @elseif (isset($HotelInfo['primary_image']))
                                <img src="{{ $HotelInfo['hotel_image'] }}" alt="">
                            @else
                                <img src="/hotel/banner/1692166141primary_img.jpg" alt="">
                            @endif
                        </figure>
                    </div>
                    <div class="col-md-6 ps-md-2">
                        <div class="row">
                            @foreach ($HotelInfo['media'] as $key => $media)
                            {{-- @if(File::exists(str_replace('http://localhost/','/',$media['original_url']))) --}}
                            <div class="col-6 ps-2 pb-3">
                                @if($key < 4)
                                <figure class="moreImg"><img src="{{ str_replace('http://localhost/','/',$media['original_url']) }}" alt="">
                                    @if($key == 3)
                                        <a href="#galleryModal" class="moreimgToggle" data-bs-toggle="modal">{{ (count($HotelInfo['media'])-4) }}+ Photos</a>
                                    @endif
                                </figure>
                                @endif
                            </div>
                            {{-- @endif --}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <button class="nav-link {{ request()->get('tr') == 'd' ? 'active' : '' }}" data-bs-toggle="tab"
                                data-bs-target="#details-tab-pane" type="button">Details</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link {{ request()->get('tr') != 'd' ? 'active' : '' }} " data-bs-toggle="tab"
                                data-bs-target="#booking-tab-pane" type="button">Booking Details</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade {{ request()->get('tr') == 'd' ? 'show active' : '' }} " id="details-tab-pane">
                            <div class="detailContent">
                                <h3>Overview</h3>
                                <p>{{ $HotelInfo['description'] }}
                                </p>
                                <hr>
                                @if($Amenities)
                                    <strong class="mb-3 d-block">Amenities</strong>
                                    <ul class="amenitiesList">
                                        @foreach (array_chunk($Amenities,5)[0] as $amenitie )
                                        <li>
                                            @if($amenitie['icon'])
                                                <img src="{{ asset('amenity-icon/'.$amenitie['icon']) }}" alt="">
                                            @endif
                                            {{ Str::ucfirst($amenitie['title']) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if (count($Amenities) > 5) 
                                <div class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-outline-primary">
                                        Show all {{count($Amenities)}} Amenities
                                    </a>
                                </div>
                                @endif
                                <hr>
                                <div class="locationLandmark">
                                    <h3>Location & Nearby Landmarks</h3>
                                    <span>{{$HotelInfo['address_1'].', '.$HotelInfo['address_2'].', '.$HotelInfo['city'].', '.$HotelInfo['state'].', '.$HotelInfo['pincode']}}</span>
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d227748.43602240764!2d75.62574362961621!3d26.885421391468288!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396c4adf4c57e281%3A0xce1c63a0cf22e09!2sJaipur%2C%20Rajasthan!5e0!3m2!1sen!2sin!4v1691661383210!5m2!1sen!2sin"
                                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade {{ request()->get('tr') != 'd' ? 'show active' : '' }} " id="booking-tab-pane">
                            <div class="bookingDetailContent">
                                {{-- <strong>Select Time Slot for Booking</strong>
                                <p>Please select available time slot you will be checking in</p>
                                <div class="selectTimeCheck d-flex flex-wrap align-items-center gap-3">
                                    <div class="checkbxFilter">
                                        <input type="checkbox" name="filter" value="1" id="f1">

                                        <label class="filterLabel" for="f1">11:00 - 13:00</label>
                                    </div>
                                    <div class="checkbxFilter ">
                                        <input type="checkbox" name="filter" value="1" id="f2">
                                        <label class="filterLabel" for="f2">13:01 - 15:00</label>
                                    </div>
                                    <div class="checkbxFilter ">
                                        <input type="checkbox" name="filter" value="1" id="f3">
                                        <label class="filterLabel" for="f3">15:01 - 18:00</label>
                                    </div>
                                </div>
                                <hr> --}}
                                <strong>Select Rooms</strong>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                                @foreach ($HotelRooms as $HotelRoom)
                                <div class="SelectRooms mt-3" data-rt-id="{{$HotelRoom->id}}">
                                    <div class="roomsDetail">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <h4>{{ str_replace('-',' ',Str::ucfirst($HotelRoom->room_type)) }}</h4>
                                                <span class="d-flex align-items-center"><img src="../images/bed-icon.svg"
                                                        alt="" width="15" class="me-2">{{ str_replace('-',' ',Str::ucfirst($HotelRoom->bed_type)) }}</span>
                                                <span class="d-flex align-items-center room-occupancy" data-room-occupancy="{{$HotelRoom->occupancy}}"><img src="../images/user-icon2.svg"
                                                        alt="" width="13" class="me-2">{{ $HotelRoom->occupancy }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="selectRoomGroup mb-2">
                                                    <label>Select Rooms</label>
                                                    <select class="roomNumberSelection form-control form-select " name="booking[room-type][{{$HotelRoom->id}}][noOfroom]">
                                                        <option value="0">How Many?</option>
                                                        @for ($i = 1; $i <= $HotelRoom->no_of_room; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                        @endfor

                                                    </select>
                                                </div>
                                                @if ($HotelRoom->s_price && $HotelRoom->s_price < $HotelRoom->r_price)    
                                                <div class="d-block text-end roomPrice text-danger">
                                                    <del>INR {{ round($HotelRoom->r_price) }}</del> 
                                                    {{ round((($HotelRoom->r_price-$HotelRoom->s_price)/$HotelRoom->r_price)*100) }}% off <b>INR {{round($HotelRoom->s_price)}}</b>
                                                </div>
                                                @else
                                                <div class="d-block text-end roomPrice text-danger">
                                                    <b>INR {{round($HotelRoom->s_price)}}</b>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $RoomsAmenities = $HotelRoom->amenity;
                                            $RoomsAmenitiesArrAll = explode(',',$RoomsAmenities)??[];
                                        @endphp
                                        @if ($RoomsAmenitiesArrAll)
                                        <div class="roomFacility">
                                            <ul class="facilityList">
                                                @php 
                                                    $rai = 0; 
                                                @endphp
                                                @foreach ($RoomsAmenitiesArrAll as $raKey => $RoomsAmenity)
                                                <li>
                                                    {{Str::ucfirst($RoomsAmenity)}}
                                                </li>
                                                @if ($rai > 15)
                                                <li class="moreFacility">
                                                    {{Str::ucfirst($RoomsAmenity)}}
                                                </li>
                                                @endif
                                                @php 
                                                    $rai++; 
                                                @endphp
                                                @endforeach
                                            </ul>
                                            @if (count($RoomsAmenitiesArrAll) > 15)
                                            <button type="button" class="facilitiesToggle">More</button>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    <div class="selectMemberOuter"></div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bookingSummary">
                        {{-- <h2>Lorem Ipsum is simply dummy</h2> --}}
                        <strong>Booking Summary</strong>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="d-flex align-items-center gap-1"><img src="../images/user-icon3.svg" alt=""
                                    width="15"><span class="boSumMaNo">0</span> Member <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                    {{-- <div class="infoTooltip pb-2">
                                        <b>Employee</b>
                                        <span class="m-0 p-0">Ariana Moore</span>
                                        <b>Client</b>
                                        <span class="m-0 p-0">Veronica Cruz</span>
                                        <b>Guest</b>
                                        <span class="m-0 p-0">Leonel Gibson</span>
                                    </div> --}}
                                </div></span>
                            <span class="d-flex align-items-center gap-1"><img src="../images/bed-icon2.svg" alt=""
                                    width="15"><span class="boSumRoNo">0</span> Room <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                    {{-- <div class="infoTooltip left-auto pb-2">
                                        <b>Employee</b>
                                        <span class="m-0 p-0">Ariana Moore</span>
                                        <b>Client</b>
                                        <span class="m-0 p-0">Veronica Cruz</span>
                                        <b>Guest</b>
                                        <span class="m-0 p-0">Leonel Gibson</span>
                                    </div> --}}
                                </div></span>
                        </div>
                        <div class="bookingDate">
                            <div class="checkIn">
                                <img src="../images/checkin-icon2.svg" alt="">
                                <span>
                                    <b>Check IN</b><br>
                                    06/01/2023
                                </span>
                            </div>
                            <div class="checkOut">
                                <img src="../images/checkin-icon2.svg" alt="">
                                <span>
                                    <b>Check Out</b><br>
                                    06/01/2023
                                </span>
                            </div>
                        </div>
                        <table class="table roomTypes">
                            <tbody>
                            </tbody>
                        </table>
                        <table class="table daytotlaAmount">
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        <div class="px-3 pb-3 mt-3">
                            <button type="button" disabled class="btn btn-primary w-100 bookingConfirmBtn">Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if($similarHotels)
    <div class="similarProperties">
        <h3 class="box-title mb-4">Similar Properties</h3>
        <div class="propertiesSlider">
            @foreach ($similarHotels as $similarHotel)
            <div>
                <a href="{{ route('book-hotel',[Crypt::encrypt($similarHotel["id"])]) }}" class="propertieContent">
                    <figure>
                        @if ($similarHotel['primary_image'])
                        <img src="{{ url('hotel/banner') . '/' . $similarHotel['primary_image'] }}"
                            alt="">
                        @elseif (isset($similarHotel['primary_image']))
                            <img src="{{ $similarHotel['hotel_image'] }}" alt="">
                        @else
                            <img src="/hotel/banner/1692166141primary_img.jpg" alt="">
                        @endif
                    </figure>
                    <div class="d-flex justify-content-between align-items-start">
                        <h3>{{Str::ucfirst($similarHotel['title']) }}</h3>
                        <span class="propertieStar d-flex align-items-center"><img src="../images/star.svg"
                                alt="" width="15" class="me-1">{{ $similarHotel['rating'] }}</span>
                    </div>
                    <div class="propertiesInfo">
                        {{-- <span>
                            <img src="../images/bed-icon.svg" alt="">2
                            beds
                        </span> --}}
                        <span><img src="../images/pin.svg" alt="">{{ $similarHotel['city'].', '.$similarHotel['state'] }}</span></div>
                    <strong class="propertiePrice">₹ {{$similarHotel['startPrice']}} <b>night</b></strong>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="bookingtotal  d-flex justify-content-between w-100">
        <div class="d-flex align-items-center bookingtotalinnr flex-wrap">
            <div>
                <h2>{{ Str::ucfirst($HotelInfo['title']) }}</h2>
                {{-- <span>06/01/2023 - 07/01/2023</span> --}}
            </div>
            <div class="roomUsers d-flex mx-md-3 mx-sm-0">
                <span><img src="../images/user-blue.svg" alt="roomicon" width="19" height="19">
                    <div class="infoToggle"><span class="boSumMaNo">0</span>
                        {{-- <div class="infoTooltip tooltipmember pb-2">
                            <b>Employee</b>
                            <span class="m-0 p-0">Ariana Moore</span>
                            <b>Client</b>
                            <span class="m-0 p-0">Veronica Cruz</span>
                            <b>Guest</b>
                            <span class="m-0 p-0">Leonel Gibson</span>
                        </div> --}}
                    </div>
                </span><b>/</b>
                <span><img src="../images/roomblue-icon.svg" alt="roomicon" width="19" height="19">
                    <div class="infoToggle"><span class="boSumRoNo">0</span>
                        {{-- <div class="infoTooltip tooltipmember pb-2">
                            <b>Employee</b>
                            <span class="m-0 p-0">Ariana Moore</span>
                            <b>Client</b>
                            <span class="m-0 p-0">Veronica Cruz</span>
                            <b>Guest</b>
                            <span class="m-0 p-0">Leonel Gibson</span>
                        </div> --}}
                    </div>
                </span>
            </div>
        </div>
        <div class="d-flex">
            <div class="text-end fixBookSumAmounts"></div>
            <button type="button" disabled class="btn btn-primary px-5 mx-3 bookingConfirmBtn">Book Now</button>
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
                                <li class="selected"><img src="../images/location-icon-white.svg" alt="locationicon"
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
    <!-- Modal -->
    <div class="modal fade employeeModal guestsModal" id="guestsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Guests <span>Count : 0</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="../images/modal-close.svg" alt="" width="20"></button>
                </div>
                <div class="modal-body">
                    <div class="selectGuests">
                        {{-- <form action="" id="addTempGuest" method="post"> --}}
                            <div class="form-group mb-4">
                                <label for="">Enter Email ID *</label>
                                <input type="email" class="form-control guest_email" placeholder="Please enter Email"
                                    name="">
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Enter Number *</label>
                                <input type="number" class="form-control guest_phone" placeholder="Phone Number">
                            </div>
                            <div class="form-group text-end">
                                <button type="button" class="btn btn-primary addTempGuest">Add</button>
                            </div>
                        {{-- </form> --}}
                        <div class="table-responsive guestsTempList">
                            <span class="guestsAdd">
                                0 Guests added
                            </span>
                            <table class="table">
                                <tbody></tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary saveGuestList">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade employeeModal clientsModal" id="clientsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Clients <span>Count : 0</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="../images/modal-close.svg" alt="" width="20"></button>
                </div>
                <div class="modal-body">
                    <div class="selectGuests">
                        {{-- <form action="" id="addTempClient" method="post"> --}}
                            <div class="form-group mb-4">
                                <label for="">Enter Email ID *</label>
                                <input type="email" class="form-control client_email" placeholder="Please enter Email"
                                    name="">
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Enter Number *</label>
                                <input type="number" class="form-control client_phone" placeholder="Phone Number">
                            </div>
                            <div class="form-group text-end">
                                <button type="button" class="btn btn-primary addTempClient">Add</button>
                            </div>
                        {{-- </form> --}}
                        <div class="table-responsive clientsTempList">
                            <span class="guestsAdd">
                                2 Clients added
                            </span>
                            <table class="table">
                                <tbody></tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" class="btn btn-primary saveClientList">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade employeeModal" id="employeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Select Employees <span>Count : 2</span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="../images/modal-close.svg" alt="" width="20"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="selectEmployee">
                        <div class="form-group filter_search filter_search1 mb-1" id="filter_search1">
                            <input type="text" class="form-control" placeholder="Search Employee">
                        </div>
                        <div class="d-flex manserch">
                            <div class="">
                                <img src="../images/search-icon.svg" class="serc" alt="" width="20" height="20">
    
                            </div>
                            <ul class="d-flex flex-wrap checkselect px-2 mt-1"></ul>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="form-group">
                            @foreach ($employeesList as $key => $employee)                                
                            <div class="cstmCheckbox emplSelectionBox mb-4">
                                <input type="hidden" class="emp_name" value="{{ $employee['first_name'].' '.$employee['last_name'] }}">
                                <input type="hidden" class="emp_email" value="{{ $employee['emp_email'] }}">
                                <input type="hidden" class="phone_number" value="{{ $employee['phone_number'] }}">
                                <input type="checkbox" class="employeeId" id="termCheck-{{$key}}" value="{{ $employee['id'] }}">
                                <label for="termCheck-{{$key}}" class="pt-1 text-black"> {{ $employee['first_name'].' '.$employee['last_name'] }}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="form-group" style="text-align: center;">
                            <button class="btn btn-primary saveEmpToRoomBtn">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade galleryModal" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="galleryCarousel">   
                        @foreach ($HotelInfo['media'] as $media)
                            <div class=""><img src="{{ str_replace('http://localhost/','/',$media['original_url']) }}" alt=""></div>
                        @endforeach
                    </div>
                    <div class="slider-nav">
                        @foreach ($HotelInfo['media'] as $media)
                            <div class="">
                                <img src="{{ str_replace('http://localhost/','/',$media['original_url']) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-overly"></div>
@endsection
@section('scripts')
    @parent
    <script>
        jQuery(document).ready(function($){

            // add/remove rooms
            jQuery(document).on('change','.roomNumberSelection',function(){
                var selectedRooms = Number(jQuery(this).val());
                var empTableHtml = '<div class="selectMember" data-r-number="">'+
                                        '<div class="selectMemberGroup">'+
                                            '<h4>Select Members: <span>For Room <b class="roomNo"></b></span></h4>'+
                                            '<ul>'+
                                                '<li><button type="button" data-bs-toggle="modal" data-bs-target="#employeeModal">Employee</button></li>'+
                                                '<li><button type="button" data-bs-toggle="modal" data-bs-target="#clientsModal">Client</button></li>'+
                                                '<li><button type="button" data-bs-toggle="modal" data-bs-target="#guestsModal">Guest</button></li>'+
                                            '</ul>'+
                                        '</div>'+
                                        '<div class="table-responsive roomMemberListTable">'+
                                            '<table class="table">'+
                                                '<thead>'+
                                                    '<tr>'+
                                                        '<th>Type</th>'+
                                                        '<th>Name</th>'+
                                                        '<th>Email</th>'+
                                                        '<th>Number</th>'+
                                                        '<th></th>'+
                                                    '</tr>'+
                                                '</thead>'+
                                                '<tbody></tbody>'+
                                            '</table>'+
                                        '</div>'+
                                    '</div>';

                var addedRoomEmpList =  Number(jQuery(this).closest('.SelectRooms').find('.selectMember').length);
                var roomNeeded = Number(selectedRooms)-Number(addedRoomEmpList);
                if(roomNeeded > 0){
                    for (let index = 0; index < roomNeeded; index++) {
                       jQuery('.selectMemberOuter').append(empTableHtml); 
                    }
                }
                if(selectedRooms != 0){
                    if(addedRoomEmpList > 0){
                        var sri = 1;
                        jQuery(this).closest('.SelectRooms').find('.selectMember').each(function(){
                            if(sri <= selectedRooms){
                                jQuery(this).slideDown();
                            }else{
                                jQuery(this).slideUp();
                            }
                            sri++;
                        });
                    }
                    jQuery(this).closest('.SelectRooms').find('.selectMemberOuter').slideDown();
                }else{
                    jQuery(this).closest('.SelectRooms').find('.selectMemberOuter').slideUp();
                }
                reOrderRoomNumber();
            });
            
            jQuery(document).on('click','.facilitiesToggle',function(){
                jQuery(this).hide();
                jQuery('li.moreFacility').show();
            });

            $('.galleryModal ').on('shown.bs.modal', function (e) {
                $('.galleryModal  .slick-slider').slick('setPosition');
            })    

            // add/remove employees into rooms
            var selectedAllEmployees = [];
            var selectedEmployees = [];
            var tempClientsLists = [];
            var tempAllClientsLists = [];
            var tempGuestsLists = [];
            var tempAllGuestsLists = [];
            var empRowNumber = 0;

            // set room into model 
            $(document).on('click','.selectMemberGroup ul button',function(e) {
                selectedEmployees = [];
                e.preventDefault();
                var r_number = jQuery(this).closest('.selectMember').data('r-number');
                var rt_id = jQuery(this).closest('.SelectRooms').data('rt-id');
                var data_target = jQuery(this).data('bs-target');
                jQuery(data_target).attr('rt-id',rt_id);
                jQuery(data_target).attr('r-number',r_number);
            });

            jQuery(document).on('click','.roomMemberListTable tbody tr td button.deletBtn',function(){
                var delEmpId = jQuery(this).data('empid');
                selectedEmployees = jQuery.grep(selectedEmployees,function(emp){
                                        return delEmpId != emp.emplID;
                                    });
                selectedAllEmployees = jQuery.grep(selectedAllEmployees,function(emp){
                                            return delEmpId != emp;
                                        });
                tempAllClientsLists = jQuery.grep(tempAllClientsLists,function(client){
                                            return delEmpId != client;
                                        });
                jQuery(this).closest('tr').remove();
                
                jQuery('.roomMemberListTable').each(function(){
                    if(!jQuery(this).find('tbody tr').length){
                        jQuery(this).slideUp();
                    }
                });

                setBookingSummary();
            });

            // On select emp for room
            jQuery(document).on('change','.emplSelectionBox input.employeeId',function(){
                var emplID = jQuery(this).val();
                if(!jQuery(this).is(":checked")){
                    selectedEmployees = jQuery.grep(selectedEmployees,function(emp){
                                            return emplID != emp.emplID;
                                        });
                    selectedAllEmployees = jQuery.grep(selectedAllEmployees,function(emp){
                                            return emplID != emp;
                                        });
                }else{

                    if(selectedAllEmployees.includes(emplID)){
                        alert('This memeber already added to rooms.');
                        jQuery(this).prop('checked',false);
                        return false;
                    }

                    if(emplID){
                        var empName = jQuery(this).closest('.cstmCheckbox').find('input.emp_name').val();
                        var empEmail = jQuery(this).closest('.cstmCheckbox').find('input.emp_email').val();
                        var empPhone = jQuery(this).closest('.cstmCheckbox').find('input.phone_number').val();
                        var empObj = {'emplID':emplID,'empName':empName,'empEmail':empEmail,'empPhone':empPhone};
                        selectedAllEmployees.push(emplID);
                        selectedEmployees.push(empObj);
                    }                    
                }
                var roomSelectedEmp = '';
                selectedEmployees?.forEach(element => {
                    roomSelectedEmp +=  '<li id="myDIV" style="display: block;"><span class="searchbtnadd">'+element.empName+'</span></li>';
                });
                jQuery(this).closest('.employeeModal').find('.checkselect').html(roomSelectedEmp);
            });

            // Set employee into front
            jQuery(document).on('click','.saveEmpToRoomBtn',function(){
                var rt_id = jQuery(this).closest(".employeeModal").attr('rt-id');
                var r_number = jQuery(this).closest(".employeeModal").attr('r-number');

                let selectedMembers = getRoomMembers(rt_id,r_number)+Number(selectedEmployees.length);
                let memberLimit = allowedMembers(rt_id);
                if(selectedMembers > memberLimit){
                    alert('This room members limit exhausted.');
                    return false;
                }

                if(selectedEmployees.length){
                    selectedEmployees.forEach(function(empDetl){
                        var memberListTr =  '<tr>'+
                                                '<td>Employee</td>'+
                                                '<td>'+empDetl.empName+'</td>'+
                                                '<td>'+empDetl.empEmail+'</td>'+
                                                '<td>'+empDetl.empPhone+'</td>'+
                                                '<td>'+
                                                    '<button type="button" data-empId="'+empDetl.emplID+'" class="deletBtn">'+
                                                        '<i class="bi bi-trash3"></i>'+
                                                    '</button>'+
                                                    '<div class="roomTypeInfoFields" style="display:none">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][membertype]" value="empolyee">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberID]" value="'+empDetl.emplID+'">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberName]" value="'+empDetl.empName+'">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberEmail]" value="'+empDetl.empEmail+'">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberPhone]" value="'+empDetl.empPhone+'">'+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>';
                        jQuery('.SelectRooms[data-rt-id="'+rt_id+'"] .selectMember[data-r-number="'+r_number+'"] .roomMemberListTable table tbody').append(memberListTr);
                        empRowNumber++;
                    });
                }
                $("#employeeModal").modal('hide');
                jQuery('.emplSelectionBox input.employeeId').each(function(){
                    jQuery(this).prop('checked',false);
                });
                jQuery('.employeeModal .checkselect').html('');
                hidShowwMemberTable();
                setBookingSummary();
            });

            jQuery(document).on('keyup','.filter_search input',function(){
                var searchText = jQuery(this).val();
                if(searchText){
                jQuery(this).closest('.employeeModal').find('.emplSelectionBox').each(function(){
                        var empNameSr = jQuery(this).find('.emp_name').val();
                        if (empNameSr.indexOf(searchText) > -1){
                            jQuery(this).show();
                        }else{
                            jQuery(this).hide();
                        }
                    });
                }else{
                    jQuery(this).closest('.employeeModal').find('.emplSelectionBox').show();
                }
            });

            // Add/remove clients
            jQuery(".addTempClient").on('click',function(e){
                var clientEmail = jQuery('.clientsModal input.client_email').val();
                var clientPhone = jQuery('.clientsModal input.client_phone').val();
                if(!clientEmail || !clientPhone){
                    alert('Please fill all required fields');
                    return false;
                }

                var addedClient = jQuery.grep(tempAllClientsLists,function(client){
                                        return clientEmail == client;
                                    });
                if(addedClient.length > 0){
                    alert('This Client Already Added');
                    return false;
                }

                var clientObj = {'clientEmail':clientEmail,'clientPhone':clientPhone};
                var tempClientHtml =    '<tr>'+
                                            '<td></td>'+
                                            '<td>'+clientObj.clientEmail+'</td>'+
                                            '<td>'+clientObj.clientPhone+'</td>'+
                                            '<td><button data-client="'+clientObj.clientEmail+'" type="button" class="closeClients"><i class="bi bi-x-lg"></i></button>'+
                                            '</td>'+
                                        '</tr>';
                jQuery('.clientsTempList table tbody').append(tempClientHtml);
                jQuery('.clientsTempList').slideDown();

                tempClientsLists.push(clientObj);
                tempAllClientsLists.push(clientEmail);

                jQuery('.clientsModal input.client_email').val('');
                jQuery('.clientsModal input.client_phone').val('');

                tempClientListReorder();
            });


            // Add/remove guests
            jQuery(".addTempGuest").on('click',function(e){
                var guestEmail = jQuery('.guestsModal input.guest_email').val();
                var guestPhone = jQuery('.guestsModal input.guest_phone').val();
                if(!guestEmail || !guestPhone){
                    alert('Please fill all required fields');
                    return false;
                }

                var addedGuest = jQuery.grep(tempAllGuestsLists,function(guest){
                                        return guestEmail == guest;
                                    });
                if(addedGuest.length > 0){
                    alert('This Guest Already Added');
                    return false;
                }

                var guestObj = {'guestEmail':guestEmail,'guestPhone':guestPhone};
                var tempGuestHtml =    '<tr>'+
                                            '<td></td>'+
                                            '<td>'+guestObj.guestEmail+'</td>'+
                                            '<td>'+guestObj.guestPhone+'</td>'+
                                            '<td><button data-guest="'+guestObj.guestEmail+'" type="button" class="closeGuests"><i class="bi bi-x-lg"></i></button>'+
                                            '</td>'+
                                        '</tr>';
                jQuery('.guestsTempList table tbody').append(tempGuestHtml);
                jQuery('.guestsTempList').slideDown();

                tempGuestsLists.push(guestObj);
                tempAllGuestsLists.push(guestEmail);

                jQuery('.guestsModal input.guest_email').val('');
                jQuery('.guestsModal input.guest_phone').val('');

                tempClientListReorder();
            });

            jQuery(document).on('click','.clientsTempList table .closeClients',function(){
                var delClient = jQuery(this).data('client');
                tempAllClientsLists = jQuery.grep(tempAllClientsLists,function(client){
                                            return delClient != client;
                                        });
                tempClientsLists = jQuery.grep(tempClientsLists,function(client){
                                            return delClient != client.clientEmail;
                                        });

                jQuery(this).closest('tr').remove();
                tempClientListReorder();
            });

            jQuery(document).on('click','.guestsTempList table .closeGuests',function(){
                var delGuest = jQuery(this).data('guest');
                tempAllGuestsLists = jQuery.grep(tempAllGuestsLists,function(guest){
                                            return delGuest != guest;
                                        });
                tempGuestsLists = jQuery.grep(tempGuestsLists,function(guest){
                                            return delGuest != guest.guestEmail;
                                        });

                jQuery(this).closest('tr').remove();
                tempGuestListReorder();
            });

            // Clients add into front
            jQuery(document).on('click','.saveClientList',function(){
                var rt_id = jQuery(this).closest(".employeeModal").attr('rt-id');
                var r_number = jQuery(this).closest(".employeeModal").attr('r-number');

                let selectedMembers = getRoomMembers(rt_id,r_number)+Number(tempClientsLists.length);
                let memberLimit = allowedMembers(rt_id);
                if(selectedMembers > memberLimit){
                    alert('This room members limit exhausted.');
                    return false;
                }

                if(tempClientsLists.length){
                    tempClientsLists.forEach(function(client){
                        var memberListTr =  '<tr>'+
                                                '<td>Client</td>'+
                                                '<td></td>'+
                                                '<td>'+client.clientEmail+'</td>'+
                                                '<td>'+client.clientPhone+'</td>'+
                                                '<td>'+
                                                    '<button type="button" data-empId="'+client.clientEmail+'" class="deletBtn">'+
                                                        '<i class="bi bi-trash3"></i>'+
                                                    '</button>'+
                                                    '<div class="roomTypeInfoFields" style="display:none">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][membertype]" value="client">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberID]" value="">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberName]" value="">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberEmail]" value="'+client.clientEmail+'">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberPhone]" value="'+client.clientPhone+'">'+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>';
                        jQuery('.SelectRooms[data-rt-id="'+rt_id+'"] .selectMember[data-r-number="'+r_number+'"] .roomMemberListTable table tbody').append(memberListTr);
                        empRowNumber++;
                    });
                }
                jQuery('.clientsTempList table tbody').html('');
                tempClientsLists = [];
                $("#clientsModal").modal('hide');
                jQuery('.clientsTempList').hide();
                tempClientListReorder();
                hidShowwMemberTable();
                setBookingSummary();
            });

            // Guests add into front
            jQuery(document).on('click','.saveGuestList',function(){
                var rt_id = jQuery(this).closest(".employeeModal").attr('rt-id');
                var r_number = jQuery(this).closest(".employeeModal").attr('r-number');

                let selectedMembers = getRoomMembers(rt_id,r_number)+Number(tempGuestsLists.length);
                let memberLimit = allowedMembers(rt_id);
                if(selectedMembers > memberLimit){
                    alert('This room members limit exhausted.');
                    return false;
                }

                if(tempGuestsLists.length){
                    tempGuestsLists.forEach(function(guest){
                        var memberListTr =  '<tr>'+
                                                '<td>Guest</td>'+
                                                '<td></td>'+
                                                '<td>'+guest.guestEmail+'</td>'+
                                                '<td>'+guest.guestPhone+'</td>'+
                                                '<td>'+
                                                    '<button type="button" data-empId="'+guest.guestEmail+'" class="deletBtn">'+
                                                        '<i class="bi bi-trash3"></i>'+
                                                    '</button>'+
                                                    '<div class="roomTypeInfoFields" style="display:none">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][membertype]" value="guest">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberID]" value="">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberName]" value="">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberEmail]" value="'+guest.guestEmail+'">'+
                                                        '<input type="hidden" name="booking[room-type]['+rt_id+'][room]['+r_number+'][members]['+empRowNumber+'][memberPhone]" value="'+guest.guestPhone+'">'+
                                                    '</div>'+
                                                '</td>'+
                                            '</tr>';
                        jQuery('.SelectRooms[data-rt-id="'+rt_id+'"] .selectMember[data-r-number="'+r_number+'"] .roomMemberListTable table tbody').append(memberListTr);
                        empRowNumber++;
                    });
                }
                jQuery('.guestsTempList table tbody').html('');
                tempGuestsLists = [];
                $("#guestsModal").modal('hide');
                jQuery('.guestsTempList').hide();
                tempClientListReorder();
                hidShowwMemberTable();
                setBookingSummary();    
            });

        });

        function allowedMembers(roomTypeId){
            let roomOcpcy = jQuery('.SelectRooms[data-rt-id="'+roomTypeId+'"] .room-occupancy').data('room-occupancy');
            // let selectedNoRoom =  jQuery('.SelectRooms[data-rt-id="'+roomTypeId+'"] .roomNumberSelection').val()??0;
            // let memberLimit = (Number(roomOcpcy)*Number(selectedNoRoom));
            return Number(roomOcpcy);
        }

        function getRoomMembers(roomTypeId,r_number){
            var totalRoomMembers = jQuery('.SelectRooms[data-rt-id="'+roomTypeId+'"] .selectMember[data-r-number="'+r_number+'"] .roomMemberListTable table tbody tr').length;
            return Number(totalRoomMembers);
        }

        function tempClientListReorder(){
            var roi = 1;
            jQuery('.clientsTempList table tbody tr').each(function(){
                jQuery(this).find('td:nth-child(1)').html(roi);
                roi++;
            });

            var noOfClient = jQuery('.clientsTempList table tbody tr').length;
            jQuery('.clientsTempList .guestsAdd').html(noOfClient+' Clients added');
            jQuery('.clientsModal h3.modal-title span').html('Count : '+noOfClient);
            if(!noOfClient){
                jQuery('.clientsTempList').slideUp();
            }
        }

        function tempGuestListReorder(){
            var roi = 1;
            jQuery('.guestsTempList table tbody tr').each(function(){
                jQuery(this).find('td:nth-child(1)').html(roi);
                roi++;
            });

            var noOfGuest = jQuery('.guestsTempList table tbody tr').length;
            jQuery('.guestsTempList .guestsAdd').html(noOfGuest+' Guestss added');
            jQuery('.guestsModal h3.modal-title span').html('Count : '+noOfGuest);
            if(!noOfGuest){
                jQuery('.guestsTempList').slideUp();
            }
        }

        function reOrderRoomNumber(){
            jQuery('.SelectRooms').each(function(){
                var roi = 1;
                jQuery(this).find('.selectMember').each(function(){
                    jQuery(this).find('b.roomNo').html(roi);
                    jQuery(this).attr('data-r-number',roi);
                    roi++;
                });
            });
        }

        function hidShowwMemberTable(){
            jQuery(".SelectRooms").each(function(){
                jQuery(this).find('.roomMemberListTable').each(function(){
                    if(jQuery(this).find('table tbody tr').length){
                        jQuery(this).slideDown();
                    }else{
                        jQuery(this).slideUp();
                    }
                }); 
            });
        }

        function setBookingSummary(){
            var formData = new FormData(document.getElementById('bookingDetailsInfo'));

            jQuery('.bookingConfirmBtn').attr('disabled','disabled');
            jQuery.ajax({
                dataType:'json',
                method:'POST',
                url:'{{ route('booking-summary') }}',
                data:formData,
                contentType: false,
                processData: false,
                success:function(response){
                    jQuery('.boSumMaNo').html(response.bookingSummary.noOfMembers);
                    jQuery('.boSumRoNo').html(response.bookingSummary.noOfTotlaRooms);
                    var roomTypeSum = '';
                    response.bookingSummary.roomTypes?.forEach(function(room){
                        if(room.price && room.noOfmembers){
                            roomTypeSum += '<tr><td>'+room.title+'</td><td class="text-end">₹'+room.price+'X'+room.noOfmembers+'</td></tr>'
                        }
                    });

                    
                    jQuery('table.roomTypes tbody').html(roomTypeSum??'');
                    
                    var totalDayAmountHtml = '<tr><td>X'+response.bookingSummary.totalDay+' Days Charges</td><td class="text-end">₹'+response.bookingSummary.totalDayPrice+'</td></tr>';
                    jQuery('table.daytotlaAmount tbody').html(roomTypeSum?totalDayAmountHtml:'');

                    var totalAmountHtml = '<tr><td>Total</td><td class="text-end"><b>₹'+response.bookingSummary.totalDayPrice+'</b></td></tr>';
                    jQuery('table.daytotlaAmount tfoot').html(roomTypeSum?totalAmountHtml:'');
                    var fixedBookingSum = '<span class="text-end">Total</span><h4 class="fw-semibold m-0">₹ '+response.bookingSummary.totalDayPrice+' </h4>';
                    jQuery('.fixBookSumAmounts').html(roomTypeSum?fixedBookingSum:'');

                    if(roomTypeSum){
                        jQuery('.bookingConfirmBtn').removeAttr('disabled');
                    }else{
                        jQuery('.bookingConfirmBtn').attr('disabled','disabled');
                    }

                    console.log(response);
                }
            });
        }

    </script>
@endsection
