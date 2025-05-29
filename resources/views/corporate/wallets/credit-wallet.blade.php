@extends('layouts.corporate')
@section('content')
    <!-- USE FOR MOBILE START-->
    <div class="dashboardHeading">
        <h2>My Credit Wallet</h2>
        <p>Welcome Back,</p>
    </div>
    <!-- USE FOR MOBILE END-->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="balance-available rounded-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 total-spends-firstcol">
                            <figure class="m-0">
                                <img src="../images/available-balance.svg" width="52px" height="52px" alt="wallet">
                            </figure>
                            <div>
                                <span class="text-white">Available Balance</span>
                                <h5 class="text-white fw-semibold">₹ XXXXX <sub class="fw-light"> INR</sub>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 total-spends">
                            <figure class="m-0">
                                <img src="../images/limit-icon.svg" width="52px" height="52px" alt="wallet">
                            </figure>
                            <div>
                                <span>Total Spends</span>
                                <h5 class="fw-semibold">₹ XXXXX <sub class="fw-light"> INR</sub>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="transaction">
                    <div class="row my-4">
                        <div class="col-lg-3 col-md-3 col-sm-6 ">
                            <div class="">
                                <label class="form-label last_f m-0 d-block">Last</label>
                                <select class="customSelect">
                                    <option value="2">30 Days</option>
                                    <option value="2">45 Days</option>
                                    <option value="1">60 Days</option>
                                    <option value="3">90 Days</option>
                                    <option value="3">120 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6  ">
                            <div class="transation-inner">
                                <span>Transactions</span>
                                <h4 class="mt-2">40</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 ">
                            <div class="transation-inner">
                                <span>Hotels Booked</span>
                                <h4 class="mt-2">40</h4>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 ">
                            <div class="transation-inner">
                                <span>Spenditure</span>
                                <h4 class="mt-2">₹ XXXXX</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-3 position-relative Deposit-money-colum">
            <div class="position-absolute payment-regin">
                <div class="d-flex align-items-center">
                    <figure class="m-0 pe-2">
                        <img src="../images/plus.svg" alt="plus" width="25" height="25">
                    </figure>
                    <div>
                        <span class="fw-semibold">Pay and manage your Credit Wallet</span>
                    </div>
                </div>
            </div>
            <div class="deposit-money box h-100">
                <div class="payment-method">
                    <div class="methhod-innr">
                        <div class="d-flex gap-2 align-items-center">
                            <figure class="m-0">
                                <img src="../images/active-name.svg" alt="flag" width="55px" height="55px">
                            </figure>
                            <div class="all-method">
                                <h6 class="m-0">Joey Miller</h6>
                                <span class="d-block">Loan Number : 81df5441b614as4516</span>
                                <button class="activebtn">ACTIVE</button>
                            </div>
                        </div>
                        <div class="Payment-all">
                            <div class="row g-2 mt-1">
                                <div class="col-md-6 h-100">
                                    <div class="payment-chouse h-100">
                                        <h6 class="payment-check">₹ XXXXX</h6>
                                        <p class="duedate mb-1">Amt due for month <b>Jan</b></p>
                                    </div>
                                </div>
                                <div class="col-md-6 h-100">
                                    <div class="payment-chouse h-100">
                                        <div class="d-flex align-items-center justify-content-between walletchart">
                                            <canvas class="progress-bar" id="myCharts"></canvas>
                                            <div>
                                                <h6 class="payment-check">₹ XXXXX</h6>
                                                <p class="duedate mb-0">Recent spends</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex payment-chouse  justify-content-between">
                                        <div class="three-daysdue payment-chouse p-0 w-50">
                                            <h6 class=" px-2 mb-0">DUE IN 3 DAYS</h6>
                                            <p class="text-black fw-bold py-2 bg-white mb-0">₹ XXXXX</p>
                                        </div>
                                        <div class=" d-flex align-items-center ">
                                            <button type="button" class="pay-now fw-normal">Pay Now
                                                <i class="bi bi-arrow-right bg-transparent mt-1 ms-2"></i>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="payment-chouse w-100 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Statement Summary</h6>
                                            <p class=" ratingB py-1 mb-0">for the month June 23</p>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-primary" data-bs-target="#Views"
                                                data-bs-toggle="modal">View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="box overflow-auto">
                <div class="table-responsive all-transition">
                    <table class="w-100" class="">
                        <div class="d-flex justify-content-between align-items-center border-bottom overflow-auto">
                            <h3 class="box-title mb-md-0">All Transaction</h3>
                            <div class="applyFilter mb-2">
                                <strong class="d-none d-md-block">
                                    <span>Apply Filter</span>
                                    3 Selected
                                </strong>
                                <button type="button" class="filterToggle" data-bs-toggle="offcanvas"
                                    data-bs-target="#filtercanvas" role="button"
                                    aria-controls="filtercanvas"><lottie-player src="../images/filter.json"
                                        background="transparent" speed="1" style="width: 40px; height: 40px;" loop
                                        autoplay></lottie-player></button>
                            </div>
                        </div>
                        <tr class="border-bottom my-2">
                            <th class="py-2 fw-normal" width=>Type</th>
                            <th class="fw-normal">Date</th>
                            <th class="fw-normal">Transaction ID</th>
                            <th class="fw-normal">Amount</th>
                            <th class="fw-normal text-center" align="center">Status</th>
                        </tr>
                        <tr class="border-bottom">
                            <td class="py-2 ">
                                <div class="d-flex gap-3 ">
                                    <figure class="mb-0">
                                        <img src="../images/hotel.svg" alt="hotel" width="36px" height="36px">
                                    </figure>
                                    <div class="transation-table">
                                        <h6 class="text-black mb-0">Hotel</h6>
                                        <span>My Wallet</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6 class="text-black mb-0">June 05, 2023</h6>
                                    <span>09:30 AM</span>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> HIUJ78FA</h6>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> ₹ XXXX</h6>
                                </div>
                            </td>
                            <td align="center">
                                <div class="transation-table pending-approvel">
                                    <h6> Pending Approval</h6>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="py-2">
                                <div class="d-flex gap-3 ">
                                    <figure class="mb-0">
                                        <img src="../images/hotel.svg" alt="hotel" width="36px" height="36px">
                                    </figure>
                                    <div class="transation-table">
                                        <h6 class="text-black mb-0">Hotel</h6>
                                        <span>My Wallet</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6 class="text-black mb-0">June 05, 2023</h6>
                                    <span>09:30 AM</span>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> HIUJ78FA</h6>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> ₹ XXXX</h6>
                                </div>
                            </td>
                            <td align="center">
                                <div class="transation-table pending-approvel ">
                                    <h6 class="apprvl"> Approval</h6>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="py-2">
                                <div class="d-flex gap-3 ">
                                    <figure class="mb-0">
                                        <img src="../images/hotel.svg" alt="hotel" width="36px" height="36px">
                                    </figure>
                                    <div class="transation-table">
                                        <h6 class="text-black mb-0">Hotel</h6>
                                        <span>My Wallet</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6 class="text-black mb-0">June 05, 2023</h6>
                                    <span>09:30 AM</span>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> HIUJ78FA</h6>
                                </div>
                            </td>
                            <td>
                                <div class="transation-table">
                                    <h6> ₹ XXXX</h6>
                                </div>
                            </td>
                            <td align="center">
                                <div class="transation-table pending-approvel">
                                    <h6 class=" rejected-bg"> Rejected</h6>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Views" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered depositModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header ">
                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Statement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 p-0">
                    <div class="px-3">
                        <div class=" position-relative">
                            <a class="d-flex align-items-center justify-content-between py-3 px-3"
                                data-bs-toggle="collapse" href="#collapseExample">
                                <p class="fw-normal m-0">Select Month</p>
                                <h6 class="mb-0 heading-statement">JAN</h6>
                            </a>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body border-0 p-0 position-absolute bg-white">
                                    <ul class="bg-white statement-month gap-3 d-flex flex-wrap">
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r1" />
                                                <label class="month-selected" for="r1">JAN</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r2" />
                                                <label class="month-selected" for="r2">FAB</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r3" />
                                                <label class="month-selected" for="r3">MAR</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r4" />
                                                <label class="month-selected" for="r4">APR</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r5" />
                                                <label class="month-selected" for="r5">MAY</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r6" />
                                                <label class="month-selected" for="r6">JUN</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r7" />
                                                <label class="month-selected" for="r7">JULY</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r8" />
                                                <label class="month-selected" for="r8">AUG</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r9" />
                                                <label class="month-selected" for="r9">SEPT</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r10" />
                                                <label class="month-selected" for="r10">OCT</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r11" />
                                                <label class="month-selected" for="r11">NOV</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r12" />
                                                <label class="month-selected" for="r12">DEC</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <a class="d-flex align-items-center justify-content-between py-3 px-3"
                                data-bs-toggle="collapse" href="#collapseExample1">
                                <p class="fw-normal m-0">Select Year</p>
                                <h6 class="mb-0 heading-statement">2023</h6>
                            </a>
                            <div class="collapse" id="collapseExample1">
                                <div class="card card-body border-0 p-0 position-absolute bg-white">
                                    <ul class="bg-white statement-month gap-3 d-flex flex-wrap">
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r00" />
                                                <label class="month-selected" for="r00">2022</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r22" />
                                                <label class="month-selected" for="r22">2022</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r33" />
                                                <label class="month-selected" for="r33">2023</label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="invisible-checkboxes">
                                                <input type="checkbox" name="rGroup" value="1" id="r44" />
                                                <label class="month-selected" for="r44">2024</label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="px-3">
                                <div class="text-center ">
                                    <lottie-player src="../images/statementlottie.json" background="transparent"
                                        class=" mx-auto" speed="1" style="width: 170px; height: 170px;"
                                        loop="" autoplay=""></lottie-player>
                                </div>
                                <div class="g-2">
                                    <button type="button" class="btn btn-primary w-100 mb-2 apprvl">View</button>
                                    <button type="button" class="btn btn-primary w-100">Download</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                </div>
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
                            <div class="d-flex align-items-center justify-content-between">
                                <label>I'm searching for</label>
                                <label class="clearFilter"><u>Clear Filters</u></label>
                            </div>
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
                    <button type="button" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>

    </div>
    <div class="menu-overly"></div>
@endsection
@section('scripts')
    @parent
@endsection
