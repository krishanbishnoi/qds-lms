@extends('layouts.corporate')
@section('content')
    <div class="dashboardHeading">
        <h1>My Invoices</h1>
        <div class="d-flex align-items-center justify-content-between">
            <p>Welcome Back,</p>
            <div class=" position-relative">
                <a class="d-flex align-items-center gap-2 py-3 px-3" data-bs-toggle="collapse" href="#collapseExample">
                    <img src="../images/calendarImg.svg" alt="" width="13" height="13">
                    <p class="fw-normal m-0">July 2023</p>
                    <img src="../images/dropdown.svg" alt="" width="15" height="15">
                </a>
                <div class="collapse invoice-collapse" id="collapseExample">
                    <div class="card card-body border-0 position-absolute bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0">Select Month</p>
                            <p class="mb-0 BlueTxt fw-bold">JAN</p>
                        </div>
                        <ul class="bg-white statement-month gap-3 d-flex mt-3 flex-wrap">
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
                        <hr>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Select Year</p>
                            <p class="mb-0 BlueTxt fw-bold">2023</p>
                        </div>
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

            </div>
        </div>
    </div>
    <div class="invoiceFilter">
        <div class="invoiceTab">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#paidTab"
                        type="button">Paid</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pendingTab"
                        type="button">Pending</button>
                </li>
            </ul>
        </div>
        <div class="invoiceSearch">
            <input class="form-control" type="text" placeholder="Invoice #">
            <div class="dropdown">
                <a class="pb-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../images/setting-icon.svg" alt="" width="38" height="38">
                </a>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item border-bottom" href="#">Action</a></li>
                    <li><a class="dropdown-item border-bottom" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="paidTab">
            <div class="table-responsive invoiceTable">
                <table class="table">
                    <tr>
                        <th>INVOICE</th>
                        <th>AMOUNT</th>
                        <th>DATE</th>
                        <th>MEMBER/S</th>
                        <th>DESTINATION</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>

                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="pendingTab">
            <div class="table-responsive invoiceTable">
                <table class="table">
                    <tr>
                        <th>INVOICE</th>
                        <th>AMOUNT</th>
                        <th>DATE</th>
                        <th>MEMBER/S</th>
                        <th></th>
                        <th>DESTINATION</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-primary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-secondary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-secondary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#Tra4568456</td>
                        <td>₹ 1515</td>
                        <td class="invoiceDate">21/07/2023 - 23/07-2023</td>
                        <td>3 <div class="infoToggle"><i class="bi bi-info-circle"></i>
                                <div class="infoTooltip">
                                    <b>Employee</b>
                                    <span>Ariana Moore</span>
                                    <b>Client</b>
                                    <span>Veronica Cruz</span>
                                    <b>Client</b>
                                    <span>Leonel Gibson</span>
                                </div>
                            </div>
                        </td>
                        <td><a href="" class="btn btn-secondary">Pay Now</a></td>
                        <td>Mumbai</td>
                        <td>
                            <div class="moreDropwon dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu">
                                    <a href="" class="border-bottom w-100">View Details</a>
                                    <a href="">Download Invoice</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="menu-overly"></div>
@endsection
@section('scripts')
    @parent
@endsection
