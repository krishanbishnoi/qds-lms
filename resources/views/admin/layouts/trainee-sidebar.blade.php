<div class="siderbar">
    <div class="logoDiv">
        <a href="javascript:void(0)">
            <img src="{{ asset('lms-img/qdegrees-logo.svg')}}" alt="logo" width="140" height="43">
        </a>
    </div>
    <div class="menuList">
        <ul>
            <li class="active">
                <a href="javascriptvoid(0);"><i><svg xmlns="http://www.w3.org/2000/svg" id="Dashboard_Icon"
                            data-name="Dashboard Icon" width="22" height="22" viewBox="0 0 32 32">
                            <rect id="bound" width="32" height="32" fill="none" />
                            <rect id="Rectangle_62_Copy" data-name="Rectangle 62 Copy" width="4"
                                height="18" rx="1.5" transform="translate(16 5)" />
                            <rect id="Rectangle_62_Copy_2" data-name="Rectangle 62 Copy 2" width="4"
                                height="11" rx="1.5" transform="translate(9 12)" />
                            <path id="Path_95" data-name="Path 95"
                                d="M2.667,21.333h20a1.333,1.333,0,0,1,0,2.667H1.333A1.333,1.333,0,0,1,0,22.667V1.333a1.333,1.333,0,0,1,2.667,0Z"
                                transform="translate(4 4)" />
                            <rect id="Rectangle_62_Copy_4" data-name="Rectangle 62 Copy 4" width="4"
                                height="8" rx="1.5" transform="translate(23 15)" />
                        </svg></i>Dashboard</a>
            </li>
            <li>
                <a href="javascriptvoid(0);"><i>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                            viewBox="0 0 32 23.146">
                            <g id="_7d0d907872d693932cfeef7a6baeee16"
                                data-name="7d0d907872d693932cfeef7a6baeee16"
                                transform="translate(-1.09 -10.196)">
                                <path id="Path_18448" data-name="Path 18448"
                                    d="M33.414,25.564v3.141a.494.494,0,0,1-.117.311c-1.614,1.916-6.277,2.6-10.1,2.6s-8.493-.681-10.112-2.6a.489.489,0,0,1-.112-.311V25.564l9.49,2.052a3.312,3.312,0,0,0,1.449,0Z"
                                    transform="translate(-6.104 -7.897)" />
                                <path id="Path_18449" data-name="Path 18449"
                                    d="M33.09,14.512a1.111,1.111,0,0,1-.89,1.1l-1.653.36v5.367a2.4,2.4,0,0,1,1.274,2.319l-.117-.117a1.466,1.466,0,0,0-.977-.379,1.219,1.219,0,0,0-.194.015l-2.227.292a2.344,2.344,0,0,1,1.269-2.129V16.185L17.6,18.776a2.247,2.247,0,0,1-.51.053,2.149,2.149,0,0,1-.506-.053l-14.61-3.16a1.128,1.128,0,0,1,0-2.2l14.61-3.165a2.467,2.467,0,0,1,1.016,0l14.6,3.165A1.11,1.11,0,0,1,33.09,14.512ZM18.122,30.431l.077.009V25.782l-1.113.146-1.108-.146v4.658l.08-.009-1.013,2.224a.486.486,0,0,0,.885.4l1.16-2.548,1.16,2.548a.486.486,0,0,0,.885-.4ZM15,25.655,3.521,24.143a.478.478,0,0,0-.389.122c-.846.758-1.113,2.825-1.006,4.531.053.88.267,2.436,1.055,2.98a.48.48,0,0,0,.277.088.183.183,0,0,0,.053,0L15,30.551v-4.9Zm16.418-.919a2.025,2.025,0,0,0-.374-.472.478.478,0,0,0-.389-.122L19.171,25.655v4.9l11.5,1.308A.472.472,0,0,0,31,31.776C32.395,30.8,32.312,26.3,31.422,24.736Z" />
                            </g>
                        </svg>
                    </i>Trainings</a>
            </li>

        </ul>
    </div>
    <div class="logoutbox">
        <span>
            @if(!empty(Auth::user()->image))
            <i><img src="{{ USER_IMAGE_URL.Auth::user()->image}}" alt="img"></i>
            @else
            <i><img src="../front/img/profile-img.png" alt="img"></i>
            @endif
             {{ ucwords(Auth::user()->first_name .' '.Auth::user()->last_name) }}</span>
        <button type="button"><a href="{{ URL('admin/logout')}}">
            <img src="../front/img/logout-icon.svg" alt="logout"></a>
        </button>

    </div>
</div>
