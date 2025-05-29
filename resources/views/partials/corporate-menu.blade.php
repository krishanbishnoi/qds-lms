<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="logoPanal">
        <a href="/">
            <img src="../images/trawo.svg" alt="logo" width="100">
            <img class="logoIcon" src="../images/trawo2.svg" alt="logo" width="45">
        </a>
        <button type="button" class="menutoggle">
            <img src="../images/menutoggle.svg" alt="toggle">
        </button>
    </div>
    <div class="sideMenu">
        <strong>Main Menu</strong>
        <ul>
            <li><a class="{{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><img
                        src="{{ asset('corporate/images/dashboard-icon.svg') }}" alt="" width="23"
                        height="23">
                    Dashboard</a></li>
            <li><a class="{{ request()->is('employees') ? 'active' : '' }}" href="{{ route('employees.index') }}"><img
                        src="{{ asset('corporate/images/employees-icon.svg') }}" alt="" width="23"
                        height="23"> My
                    Employees</a></li>
        </ul>
        <strong>Booking</strong>
        <ul>
            <li><a class="{{ request()->is('book-hotel') ? 'active' : '' }}" href="{{ route('book-hotel.index') }}"><img
                        src="{{ asset('corporate/images/book-hotel-icon.svg') }}" alt="" width="23"
                        height="23"> Book
                    Hotels</a></li>
            <li><a class="{{ request()->is('bookings') ? 'active' : '' }}" href="{{ route('bookings') }}"><img
                        src="{{ asset('corporate/images/my-bookings-icon.svg') }}" alt="" width="23"
                        height="23"> My
                    Bookings</a></li>
        </ul>
        <strong>Finance Manager</strong>
        <ul>
            <li><a class="{{ request()->is('billing-detail') ? 'active' : '' }}" href="{{ route('billing-detail.index') }}"><img
                        src="{{ asset('corporate/images/my-billing-icon.svg') }}" alt="" width="23"
                        height="23"> My Billing
                    Details</a>
            </li>
            <li><a class="{{ request()->is('invoice') ? 'active' : '' }}" href="{{ route('invoice.index') }}"><img
                        src="{{ asset('corporate/images/my-invoice-icon.svg') }}" alt="" width="23"
                        height="23"> My
                    Invoices</a></li>
            <li><a class="{{ request()->is('prepaid-wallet') ? 'active' : '' }}" href="{{ route('prepaid-wallet.index') }}"><img
                        src="{{ asset('corporate/images/my-prepaid-wallet-icon.svg') }}" alt="" width="23"
                        height="23"> My
                    Prepaid Wallet</a>
            </li>
            <li><a class="{{ request()->is('credit-wallet') ? 'active' : '' }}" href="{{ route('credit-wallet.index') }}"><img
                        src="{{ asset('corporate/images/my-credit-wallet-icon.svg') }}" alt="" width="23"
                        height="23"> My
                    Credit Wallet</a>
            </li>
        </ul>
        <strong>Customer Desk</strong>
        <ul>
            <li><a class="{{ request()->is('help') ? 'active' : '' }}" href="{{ route('help') }}"><img
                        src="{{ asset('corporate/images/help-icon.svg') }}" alt="" width="23"
                        height="23">
                    Help</a></li>
            <li><a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a></li>
        </ul>
    </div>
</nav>
<div class="dashboardHeader">
    <div class="mobileLogo d-xl-none">
        <button type="button" class="menutoggle  me-3">
            <img src="../images/menutoggle-blue.svg" alt="toggle">
        </button>
        <a href="javascript:void(0)"> <img src="../images/trawo2.svg" alt="logo" width="35"></a>
    </div>
    <div class="userProfile d-none d-lg-flex">
        <figure>
            <img src="../images/profile-img.png" alt="">
        </figure>
        <strong>{{ Auth::user()->name }}
            <span>{{ Auth::user()->email }}</span>
        </strong>
    </div>
    <!-- Visible In mobile View Start-->
    <div class="dropdown infoDropdown d-lg-none ms-auto">
        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <lottie-player src="../images/info.json" background="transparent" speed="1"
                style="width: 40px; height: 40px;" loop autoplay></lottie-player>
        </button>
        <div class="dropdown-menu">
            <div class="userProfile">
                <figure>
                    <img src="../images/profile-img.png" alt="">
                </figure>
                <strong>Joey Miller
                    <span>joey.miller@qdegrees.org</span>
                </strong>
            </div>
            <div class="walletGroup">
                <strong>
                    Credit Balance
                    <span>₹ 50,000</span>
                </strong>
                <img src="../images/wallet.svg" alt="" width="35" height="35" class="ms-2">
            </div>
            <a href="{{ route('book-hotel.index') }}" class="btn btn-primary">Book Now</a>
        </div>
    </div>
    <!-- Visible In mobile View Start-->
    <a href="{{ route('book-hotel.index') }}" class="btn btn-primary d-none d-lg-block ms-auto">Book Now</a>
    <ul class="topInfo">
        <li>
            <div class="walletGroup d-none d-lg-flex">
                <strong>
                    Credit Balance
                    <span>₹ 50,000</span>
                </strong>
                <img src="../images/wallet.svg" alt="" width="35" height="35" class="ms-2">
            </div>
        </li>
        <li><a class="helpBtn" href="{{ route('help') }}"><img src="../images/help.svg" alt=""></a>
        </li>
        <li><a class="notificationBtn" href="javascript:void(0)"><img src="../images/notification.svg"
                    alt="" width="30"></a></li>
        <li><a href="#"
                onclick="event.preventDefault(); document.getElementById('logoutform').submit();"><button
                    type="button" class="logoutBtn"><img src="../images/logout.svg" alt="">
                </button></a></li>
    </ul>

</div>
