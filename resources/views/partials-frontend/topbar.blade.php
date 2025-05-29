<header class="mainheader">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="#"><img src="images/qdegrees-logo.svg" alt="Trawo" width="100"></a>
            <div class="navbar-collapse ms-auto" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/')? 'active' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/about-us')? 'active' : '' }}" href="/about-us">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/privacy-policy')? 'active' : '' }}" href="/privacy-policy">Privacy policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/contact-us')? 'active' : '' }}" href="/contact-us">Contact Us</a>
                    </li>
                </ul>
            </div>
            <button class="navbar-toggler" type="button">
                <i class="bi bi-list"></i>
            </button>
            <?php
            if (Auth::user()){
                if(DB::table('role_user')->where('user_id','=',Auth::user()->id)->get()->toArray()[0]->role_id == 1){
                    $dashbaordUrl = '/admin/hotels/';
                }else{
                    $dashbaordUrl = '/dashboard/';
                }
                ?>
                <a href="{{ $dashbaordUrl }}"><button
                        type="button" class="btn btn-light logoutBtn">
                        {{-- <img src="../images/logout.svg" alt=""> --}}
                        Dashboard
                    </button>
                </a>
                <?php
            }else{
                ?>
                <a href="/login" class="btn btn-light">Login</a>
                <?php
            }
            ?>
        </nav>
    </div>
</header>
<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
