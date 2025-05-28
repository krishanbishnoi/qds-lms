@extends('layouts.corporate')
@section('content')
    <div class="content">
        <div class="row flex-row-reverse">
            <div class="col-lg-8 mb-3">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h3 class="box-title mb-md-0">Content Reach Summary</h3>
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
                <ul class="bookingSummaryList mb-2 ">
                    <li>
                        <div class="box summaryTxt h-100">
                            <p class="pb-2"><img src="../images/total.svg" alt="" width="15" height="15"
                                    class="me-2">Total
                                Bookings</p>
                            <div class="d-sm-flex justify-content-between align-items-baseline">
                                <strong>1025</strong>
                                <span><b class="text-success"><img src="../images/up-green-icon.svg" alt=""
                                            width="10" height="10" class="me-1">22%</b>last month</span>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box summaryTxt h-100">
                            <p class="pb-2"><img src="../images/rating-heart.svg" alt="" width="15"
                                    height="15" class="me-2">Ratings Given</p>
                            <div class="d-sm-flex justify-content-between align-items-baseline">
                                <strong>452</strong>
                                <span><b class="text-danger"><img src="../images/down-red-icon.svg" alt=""
                                            width="10" height="10" class="me-1">22%</b>last month</span>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="totalSpends pt-2 ">
                    <h3 class="box-title mb-3"><img src="../images/spends.svg" width="20" alt=""
                            class="me-2">Total
                        Spends</h3>
                    <canvas id="myChart" width="100%" height="auto" style="max-height: 260px;"></canvas>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="dashboardHeading d-none d-lg-block">
                    <h1>Dashboard</h1>
                    <p>Welcome Back, </p>
                </div>
                <div class="box p-3 upcomingBookingGroup">
                    <h3 class="box-title mb-3">Upcoming Bookings</h3>
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
                                        <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                height="13" class="me-1">20 June - 30 June</b>
                                    </span>
                                    <span>Check In Time
                                        <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                height="13" class="me-1">02:00 - 22:00</b>
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
                                        <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                height="13" class="me-1">20 June - 30 June</b>
                                    </span>
                                    <span>Check In Time
                                        <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                height="13" class="me-1">02:00 - 22:00</b>
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
                                        <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                height="13" class="me-1">20 June - 30 June</b>
                                    </span>
                                    <span>Check In Time
                                        <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                height="13" class="me-1">02:00 - 22:00</b>
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
                                        <b><img src="../images/calendarImg.svg" alt="" width="13"
                                                height="13" class="me-1">20 June - 30 June</b>
                                    </span>
                                    <span>Check In Time
                                        <b><img src="../images/clockIcon.svg" alt="" width="13"
                                                height="13" class="me-1">02:00 - 22:00</b>
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
        <div class="trawoBooking d-md-flex justify-content-between mb-3">
            <div class="trawoBookTxt d-md-flex align-items-center">
                <div class="pe-md-4"><img src="../images/trawo.svg" alt="" width="110">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                        been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                        galley
                        of type and scrambled it to make a type specimen book.</p>
                </div>
                <a href="{{ route('book-hotel.index') }}" class="ms-auto">
                    <lottie-player src="../images/book-now-btn.json" background="transparent" speed="1"
                        style="width:180px; height:50px;" loop autoplay></lottie-player></a>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="box topSpendGroup h-100">
                    <h3 class="box-title">Top Spends</h3>
                    <div class="spendCount">
                        20K <span><b class="text-success"><img src="../images/up-green-icon.svg" alt=""
                                    width="10" height="10" class="me-1">22%</b> last month</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-lighblue" role="progressbar" style="width: 22%" aria-valuenow="15"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-sky" role="progressbar" style="width: 20%" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-blue" role="progressbar" style="width: 10%" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-pink" role="progressbar" style="width: 18%" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <table class="table">
                        <tr>
                            <td class="text-lighblue">1</td>
                            <td class="text-lighblue">Roman Bates</td>
                            <td>6K</td>
                            <td>30%</td>
                        </tr>
                        <tr>
                            <td class="text-danger">2</td>
                            <td class="text-danger">Maya Fleming</td>
                            <td>4.4K</td>
                            <td>22%</td>
                        </tr>
                        <tr>
                            <td class="text-sky">3</td>
                            <td class="text-sky">Brian Gomez</td>
                            <td>4%</td>
                            <td>20%</td>
                        </tr>
                        <tr>
                            <td class="text-blue">4</td>
                            <td class="text-blue">Amy Nelson</td>
                            <td>3.6K</td>
                            <td>18%</td>
                        </tr>
                        <tr>
                            <td class="text-pink">5</td>
                            <td class="text-pink">Harvey Price</td>
                            <td>2K</td>
                            <td>10%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-lg-8 mb-3">
                <h3 class="box-title">Departement wise Spenditure</h3>
                <canvas id="barChart" style="height: 200px;"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="box h-100">
                    <h3 class="box-title">Top 5 Hotels</h3>
                    <div class="tophotels position-relative">
                        <div class="d-flex align-items-center">
                            <span class="textstep">1</span>
                            <div class="ps-3">
                                <p class="m-0">Lorem, ipsum is simply dummy text of the </p>
                                <div class="starBox"><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/fill-star.svg" alt=""><img src="../images/fill-star.svg"
                                        alt=""><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/blank-star.svg" alt="">
                                </div>
                            </div>
                            <div class="d-flex gap-2 arrowinfo">
                                <a href="javascript:void(0)">
                                    <img src="../images/arrow.svg" alt="arrowimg" width="18" height="18">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="../images/info.svg" alt="" width="18" height="18">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tophotels position-relative">
                        <div class="d-flex align-items-center">
                            <span class="textstep">2</span>
                            <div class="ps-3">
                                <p class="m-0">Lorem, ipsum is simply dummy text of the </p>
                                <div class="starBox"><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/fill-star.svg" alt=""><img src="../images/fill-star.svg"
                                        alt=""><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/blank-star.svg" alt="">
                                </div>
                            </div>
                            <div class="d-flex gap-2 arrowinfo">
                                <a href="javascript:void(0)">
                                    <img src="../images/arrow.svg" alt="arrowimg" width="18" height="18">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="../images/info.svg" alt="" width="18" height="18">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tophotels position-relative">
                        <div class="d-flex align-items-center">
                            <span class="textstep">3</span>
                            <div class="ps-3">
                                <p class="m-0">Lorem, ipsum is simply dummy text of the </p>
                                <div class="starBox"><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/fill-star.svg" alt=""><img src="../images/fill-star.svg"
                                        alt=""><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/blank-star.svg" alt="">
                                </div>
                            </div>
                            <div class="d-flex gap-2 arrowinfo">
                                <a href="javascript:void(0)">
                                    <img src="../images/arrow.svg" alt="arrowimg" width="18" height="18">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="../images/info.svg" alt="" width="18" height="18">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tophotels position-relative">
                        <div class="d-flex align-items-center">
                            <span class="textstep">4</span>
                            <div class="ps-3">
                                <p class="m-0">Lorem, ipsum is simply dummy text of the </p>
                                <div class="starBox"><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/fill-star.svg" alt=""><img src="../images/fill-star.svg"
                                        alt=""><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/blank-star.svg" alt="">
                                </div>
                            </div>
                            <div class="d-flex gap-2 arrowinfo">
                                <a href="javascript:void(0)">
                                    <img src="../images/arrow.svg" alt="arrowimg" width="18" height="18">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="../images/info.svg" alt="" width="18" height="18">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tophotels position-relative">
                        <div class="d-flex align-items-center">
                            <span class="textstep">5</span>
                            <div class="ps-3">
                                <p class="m-0">Lorem, ipsum is simply dummy text of the </p>
                                <div class="starBox"><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/fill-star.svg" alt=""><img src="../images/fill-star.svg"
                                        alt=""><img src="../images/fill-star.svg" alt=""><img
                                        src="../images/blank-star.svg" alt="">
                                </div>
                            </div>
                            <div class="d-flex gap-2 arrowinfo">
                                <a href="javascript:void(0)">
                                    <img src="../images/arrow.svg" alt="arrowimg" width="18" height="18">
                                </a>
                                <a href="javascript:void(0)">
                                    <img src="../images/info.svg" alt="" width="18" height="18">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="box h-100">
                    <h3 class="box-title">Department wise Spenditure</h3>
                    <div>
                        <div class="chartredial" id="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
