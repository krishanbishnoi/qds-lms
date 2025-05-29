@extends('layouts.corporate')
@section('content')
    <!-- USE FOR MOBILE START-->
    <div class="dashboardHeading">
        <h2>My Prepaid Wallet</h2>
        <p>Welcome Back, Joey Miller</p>
    </div>
    <!-- USE FOR MOBILE END-->
    <div class="row">
        <div class="col-xl-8 mb-3">
            <div class="balance-available rounded-3 mb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 total-spends-firstcol">
                            <figure class="m-0">
                                <img src="../images/available-balance.svg" width="55px" height="55px" alt="wallet">
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
                                <img src="../images/total-spends.svg" width="52px" height="52px" alt="wallet">
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
                                    <!-- <option selected></option> -->
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
                        <span class="fw-semibold">Deposit Money to Wallet</span>
                        <!-- <p class="text-black m-0">India (भारत)</p> -->
                    </div>
                </div>
            </div>
            <div class="deposit-money box h-100">
                <div class="payment-method">
                    <div class="methhod-innr">
                        <div class="d-flex gap-2 align-items-center">
                            <figure>
                                <img src="../images/india.png" alt="flag" width="25px" height="25px">
                            </figure>
                            <div class="all-method">
                                <span>Payment method for the region</span>
                                <p class="text-black">India (भारत)</p>
                            </div>
                        </div>
                        <div class="Payment-all">
                            <span class="pb-3">All Methods</span>
                            <div class="row g-2 mt-2">
                                <div class="col-md-6 ">
                                    <input type="radio" id="radio1" name="method" class="d-none">
                                    <label for="radio1" class="payment-chouse pt-0 active w-100 position-relative"
                                        data-bs-target="#Deposit" data-bs-toggle="modal">
                                        <img src="../images/upi.svg" alt="upi" height="50px" width="60px">
                                        </figure>
                                        <h6 class="mb-0">UPI</h6>
                                        <div class="position-absolute trawo-watermark d-none">
                                            <img src="../images/trawo2.svg" alt="" class="rounded-pill"
                                                height="25" width="25">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" id="radio2" name="method" class="d-none">
                                    <label for="radio2" class="payment-chouse pt-0 w-100 position-relative"
                                        data-bs-target="#Phonepe" data-bs-toggle="modal">
                                        <img src="../images/phonepay.svg" alt="upi" height="50px" width="90px">
                                        </figure>
                                        <h6 class="mb-0">Phonepe</h6>
                                        <div class="position-absolute trawo-watermark d-none">
                                            <img src="../images/trawo2.svg" alt="" class="rounded-pill"
                                                height="25" width="25">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" id="radio3" name="method" class="d-none">
                                    <label for="radio3" class="payment-chouse pt-0 w-100 position-relative"
                                        data-bs-target="#netbanking" data-bs-toggle="modal">
                                        <img src="../images/netbanking.svg" alt="upi" height="50px"
                                            width="80px">
                                        </figure>
                                        <h6 class="mb-0">Net Banking</h6>
                                        <div class="position-absolute trawo-watermark d-none">
                                            <img src="../images/trawo2.svg" alt="" class="rounded-pill"
                                                height="25" width="25">
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" id="radio4" name="method" class="d-none">
                                    <label for="radio4" class="payment-chouse pt-0 w-100 position-relative"
                                        data-bs-target="#Visa" data-bs-toggle="modal">
                                        <img src="../images/visa.svg" alt="upi" height="50px" width="60px">
                                        </figure>
                                        <h6 class="mb-0">Credit/Debit Card</h6>
                                        <div class="position-absolute trawo-watermark d-none">
                                            <img src="../images/trawo2.svg" alt="" class="rounded-pill"
                                                height="25" width="25">
                                        </div>
                                    </label>
                                </div>
                                <!-- <div class="mt-4">
                                                    <button class="btn btn-secondary w-100 ">Deposite</button>
                                                </div> -->
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

    <div class="modal fade" id="Deposit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered depositModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header ">
                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Deposit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0 p-4 ">
                    <div class="">
                        <figure class="m-0">
                            <img src="../images/upi.svg" alt="upi" height="70" width="70">
                        </figure>
                        <div class="">
                            <div class="my-3">
                                <label class="form-label mb-0 fw-semibold">Enter Your UPI Address</label>
                                <div class="input-group mt-3">
                                    <input type="text"
                                        class="form-control border-0 border-bottom border-dark ps-0  rounded-0"
                                        placeholder="9874625524@paytm">
                                </div>
                            </div>
                            <div class="my-3">
                                <label class="form-label mb-0 fw-semibold my-3">Enter the amount you wish to add to your
                                    Trawo Wallet</label>
                                <div class="input-group mt-3">
                                    <input type="text"
                                        class="form-control border-0 border-bottom border-dark ps-0  rounded-0"
                                        placeholder="XXXXX ₹">
                                </div>
                                <div class="d-flex justify-content-between amount-deposit">
                                    <span>Amount of one deposite</span>
                                    <span>from 1000 to 100000</span>
                                </div>
                            </div>
                            <ul class="amount-add">
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="1" id="f1">
                                        <label class="amountoption" for="f1">₹ 1000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="2" id="f2">
                                        <label class="amountoption" for="f2">₹ 10000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="3" id="f3">

                                        <label class="amountoption" for="f3">₹ 20000</label>
                                    </div>

                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="4" id="f4">
                                        <label class="amountoption" for="f4">₹ 50000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="5" id="f5">
                                        <label class="amountoption" for="f5">₹ 100000</label>
                                    </div>
                                </li>
                            </ul>
                            <div class="my-3">
                                <button class="btn btn-primary w-100">Send Payment Link</button>
                                <div class="text-center my-2">
                                    <button class="cancle-btn"><u> Cancel</u></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Phonepe" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered depositModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header ">
                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Deposit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0 p-4">
                    <div class="">
                        <figure class="m-0">
                            <img src="../images/phonepay.svg" alt="upi" height="70" width="120">
                        </figure>
                        <div class="">

                            <div class="mb-3">
                                <label class="form-label mb-0 fw-semibold">Enter the amount you wish to add to your
                                    Trawo Wallet</label>
                                <div class="input-group my-3 mb-0">
                                    <input type="text"
                                        class="form-control border-0 border-bottom border-dark ps-0  rounded-0"
                                        placeholder="XXXXX ₹">
                                </div>
                                <div class="d-flex justify-content-between amount-deposit">
                                    <span>Amount of one deposite</span>
                                    <span>from 1000 to 100000</span>
                                </div>
                            </div>
                            <ul class="amount-add">
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="1" id="11">
                                        <label class="amountoption" for="11">₹ 1000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="2" id="12">
                                        <label class="amountoption" for="12">₹ 10000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="3" id="13">

                                        <label class="amountoption" for="13">₹ 20000</label>
                                    </div>

                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="4" id="14">
                                        <label class="amountoption" for="14">₹ 50000</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="amount-sugg">
                                        <input type="radio" name="amount" value="5" id="15">
                                        <label class="amountoption" for="15">₹ 100000</label>
                                    </div>
                                </li>
                            </ul>
                            <div class="my-3">
                                <button class="btn btn-primary w-100">Send Payment Link</button>
                                <div class="text-center my-2">
                                    <button class="cancle-btn"><u> Cancel</u></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="netbanking" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered depositModal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header ">
                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Deposit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 p-0">
                    <div class="">
                        <div class="mb-3">
                            <div class="input-group py-2 px-1 netbanking-mdl border-bottom">
                                <span class="input-group-text bg-transparent border-0 "><img
                                        src="../images/search-icon.svg " alt="search" width="30"
                                        height="25"></span>
                                <input type="text" class="form-control border-0 ps-0"
                                    placeholder="Search by bank name">
                            </div>
                        </div>
                        <div class="">
                            <div class="d-flex align-items-center py-3
                         ps-4 mb-2 net-banking">
                                <figure class="m-0">
                                    <img src="../images/sbibank-logo.svg" alt="Logo" width="30" height="30">
                                </figure>
                                <h6 class="mb-0 ms-2">SBI Internet Banking</h6>
                            </div>
                            <div class="d-flex align-items-center py-3 mb-2 net-banking ps-4">
                                <figure class="m-0">
                                    <img src="../images/axisbank-logo.png" alt="Logo" width="26" height="26">
                                </figure>
                                <h6 class="mb-0 ms-2">HDFC Net Banking</h6>
                            </div>

                            <div class="d-flex align-items-center py-3 mb-2 net-banking ps-4">
                                <figure class="m-0">
                                    <img src="../images/hdfc-logo.png" alt="Logo" width="26" height="26">
                                </figure>
                                <h6 class="mb-0 ms-2">Axis Net Banking</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Visa" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered carddetails-Modal">
            <div class="modal-content rounded-0 ">
                <div class="modal-header ">
                    <h1 class="modal-title fs-6 fw-semibold" id="exampleModalToggleLabel">Deposit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0 p-0">
                    <div class="d-flex recents-card">
                        <div class="px-3 w-50 vertical-shadow">
                            <figure class="m-0">
                                <img src="../images/visa.svg" class="visamdl-img" alt="upi" height="70"
                                    width="90">
                            </figure>
                            <h6>Deposit from Credit/Debit Card</h6>
                            <div class="">
                                <div class="my-3">
                                    <div class="input-group mt-3">
                                        <input type="text"
                                            class="form-control border-0 border-bottom border-dark ps-0 rounded-0"
                                            placeholder="Cardholder Name">
                                    </div>
                                </div>
                                <div class="my-3">
                                    <div class="input-group border-bottom border-dark  mt-3">
                                        <input type="text" class="form-control border-0  ps-0  rounded-0"
                                            placeholder="Card Number">
                                        <span class="input-group-text bg-transparent border-0 pe-0"><img
                                                src="../images/card.svg" alt="card" width="25"
                                                height="25"></span>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="input-group">
                                        <div class="">
                                            <div class="input-group border-bottom border-dark ">
                                                <input type="text" class="form-control border-0  ps-0  rounded-0"
                                                    placeholder="Exp Month">
                                                <input type="text" class="form-control border-0  ps-0  rounded-0"
                                                    placeholder="Exp Year">

                                            </div>
                                        </div>
                                        <div class="my-2">
                                            <div class="input-group border-bottom border-dark  mt-3">
                                                <input type="text" class="form-control border-0  ps-0  rounded-0"
                                                    placeholder="CVV">
                                                <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                                    <div class="infoTooltip left-auto">
                                                        <b>Loremipsum.</b>
                                                        <span>Lorem ipsum dolor sit amet.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-details my-3">
                                        <div class="form-group">
                                            <div class="cstmCheckbox">
                                                <input type="checkbox" id="termCheck">
                                                <label for="termCheck" class="pt-1"><u> Save Card Details</u></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-50 recents-cardmdl">
                            <div class="mb-2 border-bottom">
                                <h6 class="text-black my-3 ps-4">Recent Cards</h6>
                            </div>
                            <div class="px-3">
                                <ul class="upcomingBookingList scrollbar-gray">
                                    <li class="changeme p-2">
                                        <div class="cardGroup">
                                            <div class="cstmCheckbox">
                                                <input type="checkbox" id="termCheckk">
                                                <label for="termCheckk" class="pt-1"></label>
                                            </div>
                                            <h6 class="fw-semibold">Amazon ICICI Credit Card …..2540</h6>
                                            <span class="cardAdmin-name"><img src="../images/card.svg" alt=""
                                                    class="pe-2" width="40" height="40"> Rahul
                                                Jain</span>
                                            <div class="input-group border-bottom border-dark">
                                                <input type="text"
                                                    class="form-control border-0 ps-0 rounded-0 bg-transparent"
                                                    placeholder="CVV">
                                                <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                                    <div class="infoTooltip left-auto">
                                                        <b>Loremipsum.</b>
                                                        <span>Lorem ipsum dolor sit amet.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="changeme p-2">
                                        <div class="cardGroup">
                                            <div class="cstmCheckbox">
                                                <input type="checkbox" id="termCheckk2">
                                                <label for="termCheckk" class="pt-1"></label>
                                            </div>
                                            <h6 class="fw-semibold">SBI Debit Card …..5475</h6>
                                            <span class="cardAdmin-name"><img src="../images/card.svg" alt=""
                                                    class="pe-2" width="40" height="40"> Rahul
                                                Jain</span>
                                            <div class="input-group border-bottom border-dark">
                                                <input type="text"
                                                    class="form-control border-0 ps-0 rounded-0 bg-transparent"
                                                    placeholder="CVV">
                                                <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                                    <div class="infoTooltip left-auto">
                                                        <b>Loremipsum.</b>
                                                        <span>Lorem ipsum dolor sit amet.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="changeme p-2">
                                        <div class="cardGroup">
                                            <div class="cstmCheckbox">
                                                <input type="checkbox" id="termCheckk3">
                                                <label for="termCheckk" class="pt-1"></label>
                                            </div>
                                            <h6 class="fw-semibold">ICICI Credit Card …..4887</h6>
                                            <span class="cardAdmin-name"><img src="../images/card.svg" alt=""
                                                    class="pe-2" width="40" height="40"> Rahul
                                                Jain</span>
                                            <div class="input-group border-bottom border-dark">
                                                <input type="text"
                                                    class="form-control border-0 ps-0 rounded-0 bg-transparent"
                                                    placeholder="CVV">
                                                <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                                    <div class="infoTooltip left-auto">
                                                        <b>Loremipsum.</b>
                                                        <span>Lorem ipsum dolor sit amet.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-block  justify-content-start ">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="amount-enter mt-0">
                            <h6 class="fw-bold">Enter Amount</h6>
                            <p class="mb-0">₹ <span>5489</span></p>
                        </div>
                        <div class="w-50">
                            <button class="btn btn-primary w-100">Send Payment Link</button>
                        </div>
                    </div>
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
