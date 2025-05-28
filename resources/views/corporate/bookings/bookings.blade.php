@extends('layouts.corporate')
@section('content')
    <!-- USE FOR MOBILE END-->
    <div class="row">

        <div class="col-lg-4 col-md-5">
            <div class="dashboardHeading mb-3">
                <h2>My Booking</h2>
                <!-- <p>Welcome Back, Joey Miller</p> -->
            </div>

        </div>
        <div class="col-lg-8 col-md-7">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <a href="#" class="btn btn-primary ms-auto me-0 me-md-5">Explore File</a>
                <div class="applyFilter">
                    <strong class="d-none d-md-block">
                        <span>Apply Filter</span>
                        3 Selected
                    </strong>
                    <button type="button" class="filterToggle" data-bs-toggle="offcanvas" data-bs-target="#filtercanvas"
                        role="button" aria-controls="filtercanvas"><lottie-player src="../images/filter.json"
                            background="transparent" speed="1" style="width: 40px; height: 40px;" loop
                            autoplay></lottie-player></button>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mb-3">

            <ul class="nav nav-tabs tabs-formate" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                        type="button" role="tab" aria-controls="home" aria-selected="true">Completed
                        Bookings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">Upcoming
                        Bookings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                        role="tab" aria-controls="contact" aria-selected="false">Saved
                        Bookings</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="pt-4 listSec">
                        @if($CompletedBookingsList)
                        @foreach ($CompletedBookingsList as $CompletedBooking)
                        <div class="listBox">
                            <a href="booking-details.html"></a>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="d-flex flex-wrap w-100 ">
                                        <div class="hotelName border-0 p-0 mb-0 w-50 flex-wrap">
                                            <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                                class="me-2">
                                            <div>
                                                <b>{{ $CompletedBooking['title'] }}</b>
                                                <div class="d-flex align-items-center thumb gap-2">
                                                    <span>{{ $CompletedBooking['city'].' '.$CompletedBooking['state'] }}</span>
                                                    <span class="d-flex align-items-center mt-1 w-100">
                                                        <img src="../images/thumbs-up.svg" height="20" class="me-1">
                                                        4.5
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">
                                            <span>For Date/s
                                                <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('d M', strtotime($CompletedBooking['checkIn'])) . ' - ' . date('d M', strtotime($CompletedBooking['checkOut'])) }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Check In Time
                                                <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('H:i', strtotime($CompletedBooking['check_in_time'])) . ' - ' . date('H:i', strtotime($CompletedBooking['check_out_time'])) }}</b>
                                            </span>
                                        </div>

                                        <div class="aboutBooking p-0 d-block w-50 ps-md-5 ps-4">
                                            <span>Booking Id
                                                <b>
                                                    <!-- <img src="../images/calendarImg.svg" alt="" width="13" height="13" class="me-1"> -->
                                                    {{ 'TRWOBID-'.$CompletedBooking['id'] }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Total Member
                                                <b><img src="../images/user-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $CompletedBooking['no_of_employee'] }}</b>
                                            </span>
                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span class="rupy-cionn">Grand Total
                                                <b><img src="../images/rupy-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $CompletedBooking['no_of_room']*$CompletedBooking['s_price']??$CompletedBooking['r_price'] }}</b>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-invoice">
                                        <a href="#" class="btn btn-primary ms-auto mb-3">Download Inovice</a>
                                        <a href="#" class="btn btn-primary btn-primary2 ms-auto">Download
                                            Voucher</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                        @else
                        <h6>No Booking Found</h6>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="pt-4 listSec">
                        @if($UpComingBookingsList)
                        @foreach ($UpComingBookingsList as $UpComingBooking)
                        <div class="listBox">
                            <a href="booking-details.html"></a>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="d-flex flex-wrap w-100 ">
                                        <div class="hotelName border-0 p-0 mb-0 w-50 flex-wrap">
                                            <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                                class="me-2">
                                            <div>
                                                <b>{{ $UpComingBooking['title'] }}</b>
                                                <div class="d-flex align-items-center thumb gap-2">
                                                    <span>{{ $UpComingBooking['city'].' '.$UpComingBooking['state'] }}</span>
                                                    <span class="d-flex align-items-center mt-1 w-100">
                                                        <img src="../images/thumbs-up.svg" height="20" class="me-1">
                                                        4.5
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">
                                            <span>For Date/s
                                                <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('d M', strtotime($UpComingBooking['checkIn'])) . ' - ' . date('d M', strtotime($UpComingBooking['checkOut'])) }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Check In Time
                                                <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('H:i', strtotime($UpComingBooking['check_in_time'])) . ' - ' . date('H:i', strtotime($UpComingBooking['check_out_time'])) }}</b>
                                            </span>
                                        </div>

                                        <div class="aboutBooking p-0 d-block w-50 ps-md-5 ps-4">
                                            <span>Booking Id
                                                <b>
                                                    <!-- <img src="../images/calendarImg.svg" alt="" width="13" height="13" class="me-1"> -->
                                                    {{ 'TRWOBID-'.$UpComingBooking['id'] }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Total Member
                                                <b><img src="../images/user-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $UpComingBooking['no_of_employee'] }}</b>
                                            </span>
                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span class="rupy-cionn">Grand Total
                                                <b><img src="../images/rupy-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $UpComingBooking['no_of_room']*$UpComingBooking['s_price']??$UpComingBooking['r_price'] }}</b>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-invoice">
                                        <a href="#" class="btn btn-primary ms-auto mb-3">Download Inovice</a>
                                        <a href="#" class="btn btn-primary btn-primary2 ms-auto">Download
                                            Voucher</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                        @else
                        <h6>No Booking Found</h6>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="pt-4 listSec">
                        @if($SavedBookingsList)
                        @foreach ($SavedBookingsList as $SavedBooking)
                        <div class="listBox">
                            <a href="booking-details.html"></a>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="d-flex flex-wrap w-100 ">
                                        <div class="hotelName border-0 p-0 mb-0 w-50 flex-wrap">
                                            <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                                class="me-2">
                                            <div>
                                                <b>{{ $SavedBooking['title'] }}</b>
                                                <div class="d-flex align-items-center thumb gap-2">
                                                    <span>{{ $SavedBooking['city'].' '.$SavedBooking['state'] }}</span>
                                                    <span class="d-flex align-items-center mt-1 w-100">
                                                        <img src="../images/thumbs-up.svg" height="20" class="me-1">
                                                        4.5
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">
                                            <span>For Date/s
                                                <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('d M', strtotime($SavedBooking['checkIn'])) . ' - ' . date('d M', strtotime($SavedBooking['checkOut'])) }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Check In Time
                                                <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ date('H:i', strtotime($SavedBooking['check_in_time'])) . ' - ' . date('H:i', strtotime($SavedBooking['check_out_time'])) }}</b>
                                            </span>
                                        </div>

                                        <div class="aboutBooking p-0 d-block w-50 ps-md-5 ps-4">
                                            <span>Booking Id
                                                <b>
                                                    <!-- <img src="../images/calendarImg.svg" alt="" width="13" height="13" class="me-1"> -->
                                                    {{ 'TRWOBID-'.$SavedBooking['id'] }}</b>
                                            </span>

                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span>Total Member
                                                <b><img src="../images/user-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $SavedBooking['no_of_employee'] }}</b>
                                            </span>
                                        </div>
                                        <div class="aboutBooking p-0 d-block w-25">

                                            <span class="rupy-cionn">Grand Total
                                                <b><img src="../images/rupy-icon.svg" alt="" width="13"
                                                        height="13" class="me-1">{{ $SavedBooking['no_of_room']*$SavedBooking['s_price']??$SavedBooking['r_price'] }}</b>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-invoice">
                                        <a href="#" class="btn btn-primary ms-auto mb-3">Download Inovice</a>
                                        <a href="#" class="btn btn-primary btn-primary2 ms-auto">Download
                                            Voucher</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                        @else
                        <h6>No Booking Found</h6>
                        @endif
                    </div>
                </div>
            </div>




        </div>
        <div class="col-lg-4 mb-3 ">

            <div class="box p-3 upcomingBookingGroup h-100 liveBooking pb-0">
                <div class=" head d-flex align-items-baseline justify-content-between">
                    <h3 class="box-title mb-3">Live Bookings</h3>
                    <h3 class="subtitle"><a href="#"><u>View All</u></a></h3>
                </div>
                <ul class="upcomingBookingList">
                    <li>
                        <div class="bookingHotel">
                            <div class="hotelName">
                                <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                    class="me-2">
                                <strong><b>Hotel Sunshine and Resort</b><span>Jaipur, Rajasthan</span></strong>
                                <div class="customerNumber ms-auto">
                                    <img src="../images/userIcon.svg" alt="" width="14" height="14"
                                        class="me-1">5
                                </div>
                            </div>
                            <div class="aboutBooking">
                                <span>For Date/s
                                    <b><img src="../images/calendarImg.svg" alt="" width="13" height="13"
                                            class="me-1">20 June - 30 June</b>
                                </span>
                                <span>Check In Time
                                    <b><img src="../images/clockIcon.svg" alt="" width="13" height="13"
                                            class="me-1">02:00 - 22:00</b>
                                </span>
                            </div>
                            <a href="" class="arrowBtn"><img src="../images/rightArrow.svg" alt=""
                                    width="22" height="22"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bookingHotel">
                            <div class="hotelName">
                                <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                    class="me-2">
                                <strong><b>Hotel Sunshine and Resort</b><span>Jaipur, Rajasthan</span></strong>
                                <div class="customerNumber ms-auto">
                                    <img src="../images/userIcon.svg" alt="" width="14" height="14"
                                        class="me-1">5
                                </div>
                            </div>
                            <div class="aboutBooking">
                                <span>For Date/s
                                    <b><img src="../images/calendarImg.svg" alt="" width="13" height="13"
                                            class="me-1">20 June - 30 June</b>
                                </span>
                                <span>Check In Time
                                    <b><img src="../images/clockIcon.svg" alt="" width="13" height="13"
                                            class="me-1">02:00 - 22:00</b>
                                </span>
                            </div>
                            <a href="" class="arrowBtn"><img src="../images/rightArrow.svg" alt=""
                                    width="22" height="22"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bookingHotel">
                            <div class="hotelName">
                                <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                    class="me-2">
                                <strong><b>Hotel Sunshine and Resort</b><span>Jaipur, Rajasthan</span></strong>
                                <div class="customerNumber ms-auto">
                                    <img src="../images/userIcon.svg" alt="" width="14" height="14"
                                        class="me-1">5
                                </div>
                            </div>
                            <div class="aboutBooking">
                                <span>For Date/s
                                    <b><img src="../images/calendarImg.svg" alt="" width="13" height="13"
                                            class="me-1">20 June - 30 June</b>
                                </span>
                                <span>Check In Time
                                    <b><img src="../images/clockIcon.svg" alt="" width="13" height="13"
                                            class="me-1">02:00 - 22:00</b>
                                </span>
                            </div>
                            <a href="" class="arrowBtn"><img src="../images/rightArrow.svg" alt=""
                                    width="22" height="22"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bookingHotel">
                            <div class="hotelName">
                                <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                    class="me-2">
                                <strong><b>Hotel Sunshine and Resort</b><span>Jaipur, Rajasthan</span></strong>
                                <div class="customerNumber ms-auto">
                                    <img src="../images/userIcon.svg" alt="" width="14" height="14"
                                        class="me-1">5
                                </div>
                            </div>
                            <div class="aboutBooking">
                                <span>For Date/s
                                    <b><img src="../images/calendarImg.svg" alt="" width="13" height="13"
                                            class="me-1">20 June - 30 June</b>
                                </span>
                                <span>Check In Time
                                    <b><img src="../images/clockIcon.svg" alt="" width="13" height="13"
                                            class="me-1">02:00 - 22:00</b>
                                </span>
                            </div>
                            <a href="" class="arrowBtn"><img src="../images/rightArrow.svg" alt=""
                                    width="22" height="22"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bookingHotel">
                            <div class="hotelName">
                                <img src="../images/hotelIcon.svg" alt="" width="35" height="35"
                                    class="me-2">
                                <strong><b>Hotel Sunshine and Resort</b><span>Jaipur, Rajasthan</span></strong>
                                <div class="customerNumber ms-auto">
                                    <img src="../images/userIcon.svg" alt="" width="14" height="14"
                                        class="me-1">5
                                </div>
                            </div>
                            <div class="aboutBooking">
                                <span>For Date/s
                                    <b><img src="../images/calendarImg.svg" alt="" width="13" height="13"
                                            class="me-1">20 June - 30 June</b>
                                </span>
                                <span>Check In Time
                                    <b><img src="../images/clockIcon.svg" alt="" width="13" height="13"
                                            class="me-1">02:00 - 22:00</b>
                                </span>
                            </div>
                            <a href="" class="arrowBtn"><img src="../images/rightArrow.svg" alt=""
                                    width="22" height="22"></a>
                        </div>
                    </li>
                </ul>
                <!-- <lottie-player src="../images/booking.json" background="transparent" speed="1"
                                style="width: 100%; height: 100%;" loop autoplay></lottie-player> -->

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
                            placeholder="Search By Guest Name, Hotel Name, Booking ID">
                    </div>
                    <div class="p-3">
                        <!-- <div class="form-group mb-3">
                                <label>Select Date Range and Time</label>
                                <ul class="searchingList">
                                    <li class="selected"><img src="../images/location-icon-white.svg" alt="" width="18"
                                            height="18">
                                        jaipur <button type="button"><img src="../images/close-white.svg" alt=""
                                                width="16"></button></li>
                                    <li><a class="searchToggle" href="javascript:void(0);"><img src="../images/hotel-icon.svg" alt="" width="18"
                                                height="18"> Hotel</a></li>
                                    <li><a class="searchToggle" href="javascript:void(0);"><img src="../images/location-icon1.svg" alt=""
                                                width="18" height="18"> State</a></li>

                                    <li><a class="searchToggle" href="javascript:void(0);"><img src="../images/flight-icon.svg" alt="" width="18"
                                                height="18"> Fight</a></li>
                                </ul>
                            </div> -->
                        <div class="form-group mb-3">
                            <label>Select Date Range and Time</label>
                            <div class="d-sm-flex gap-3">
                                <input type="text" id="datepicker" class="dateRange" placeholder="20 June - 30 June">
                                <input type="text" id="timepicker" class="timeRange" placeholder="02:00 - 22:00">
                            </div>
                        </div>
                        <!-- <div class="form-group">
                                <label>Filter By</label>
                                <ul class="recentSearchList">
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/hotel-icon.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/location-icon1.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Rajasthan<span>India</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/location-icon2.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Jaipur<span>Rajasthan</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/flight-icon.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/hotel-icon.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Hotel Sunshine and Resort<span>Jaipur, Rajasthan</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/location-icon1.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Rajasthan<span>India</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/location-icon2.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Jaipur<span>Rajasthan</span></strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="recentSearchTxt">
                                            <figure><img src="../images/flight-icon.svg" alt="" width="32" height="32">
                                            </figure>
                                            <strong>Spice jet | Jaipur to delhi<span>25 Aug, 22:00 EST</span></strong>
                                        </a>
                                    </li>

                                </ul>
                            </div> -->
                        <div class="form-group mb-3">
                            <label>Filter By</label>
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="checkbxFilter">
                                    <input type="checkbox" name="filter" value="1" id="f1">

                                    <label class="filterLabel" for="f1"><img src="../images/checkin.svg"
                                            alt="" class="img">Check In</label>
                                </div>
                                <div class="checkbxFilter ">
                                    <input type="checkbox" name="filter" value="1" id="f2">
                                    <label class="filterLabel" for="f2"><img src="../images/checkedout.svg"
                                            alt="">Checked Out</label>
                                </div>
                                <div class="checkbxFilter ">
                                    <input type="checkbox" name="filter" value="1" id="f3">
                                    <label class="filterLabel" for="f3"><img src="../images/no show.svg"
                                            alt="">No
                                        Show</label>
                                </div>
                                <div class="checkbxFilter ">
                                    <input type="checkbox" name="filter" value="1" id="f4">
                                    <label class="filterLabel" for="f4"><img src="../images/cancelled.svg"
                                            alt="">Cancelled</label>
                                </div>
                            </div>
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
                    <button type="button" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>

    </div>
@endsection
@section('scripts')
    @parent
@endsection
