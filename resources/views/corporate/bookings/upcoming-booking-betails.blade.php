@extends('layouts.corporate')
@section('content')
    <div class="content">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="dashboardHeading">
                        <h2 class="mb-4">My Employees</h2>
                        <div class="SubmitDetail box">
                            <h6 class="text-black mb-3 fw-semibold">Add Employees</h6>
                            <div class="row submitContent">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Employee First Name</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Shivam">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Employee Last Name</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Singh">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Employee ID</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="HLB00125">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Department</label>
                                        <div class="input-group">
                                            <select
                                                class="form-select rounded-0 bg-transparent border-0 border-bottom border-dark pe-0"
                                                aria-label="Default select example">
                                                <option selected>IT Department</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Designation</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Manager">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Employee Phone Number</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="09988776655">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Employee Email ID</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Shivam.singh@xyz.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Manager Email</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Raj@xyz.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Location</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Jaipur">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-0">Business Unit</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control border-0 border-bottom border-dark ps-0"
                                                placeholder="Malviya Nagar">
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 AddEmployees">
                                    <button class="btn btn-primary w-100 my-3" data-bs-target="#Submit"
                                        data-bs-toggle="modal">Submit</button>
                                    <p class="text-black mb-0">Lorem Ipsum is simply dummy text of the printing and
                                        typesetting industry.</p>
                                    <a href="javascript:void(0)" class="my-4 d-block"><u>View Sample</u></a>
                                    <button type="button"
                                        class="btn btn-primary d-flex justify-content-center w-100 text-center mx-auto"><span
                                            class="plusicon me-2 "><i class="bi bi-plus"></i></span> Employees Bulk
                                        Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="Employeeslist">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h6 class="m-0"><img src="../images/staff.png" alt="icon-employee" width="34"
                                        height="34"> Employees List</h6>
                            </div>
                            <div class="input-group w-50 search-responsiveone">
                                <input type="text" class="form-control employee-input position-relative rounded-1"
                                    placeholder="search">
                                <span class="position-absolute search-span"><i class="bi bi-search"></i></span>
                            </div>
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
                        <!-- <div class="input-group search-responsivemob w-50">
                            <input type="text" class="form-control employee-input position-relative rounded-1"
                                placeholder="search">
                            <span class="position-absolute search-span"><i class="bi bi-search"></i></span>
                        </div> -->
                        <div class="w-100">
                            <div class="table responsive employeeslistTable upcomingBookingList">
                                <table class="w-100">
                                    <tr class="position-sticky top-0" style="background-color: #67BEC5;">
                                        <th>Employee Name</th>
                                        <th>Id</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Cont. No</th>
                                        <th>Email</th>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    <tr>
                                        <td>Rohan Singh</td>
                                        <td>HLB00125</td>
                                        <td>IT Department</td>
                                        <td>Assistant Manager</td>
                                        <td>09988776655</td>
                                        <td>Shivam.singh@xyz.com</td>
                                    </tr>
                                    
                                </table>
                            </div>
                            <div class="pagination justify-content-end">
                                <button type="button" class="btn-pegination">
                                    <i class="bi bi-chevron-left bg-transparent p-0"></i>
                                </button>
                                <ul class="d-flex pagination-page mx-2">
                                    <li>
                                        <a href="#" class="page-link border-0 page-link--current">1</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">1</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">2</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">3</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">4</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">5</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link border-0">6</a>
                                    </li>
                                </ul>
                                <button type="button"  class="btn-pegination">
                                    <i class="bi bi-chevron-right bg-transparent p-0"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
    </div>
@endsection
@section('scripts')
    @parent
@endsection
