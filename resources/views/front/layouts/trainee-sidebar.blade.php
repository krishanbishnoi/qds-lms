<div class="siderbar">
    <div class="logoDiv">
        <a href="{{ route('front.dashboard') }}">
            <img src="{{ asset('lms-img/qdegrees-logo.svg') }}" alt="logo" width="140" height="43">
        </a>
    </div>
    <div class="menuList">
        <ul>
            <li>
                <a href="{{ route('front.dashboard') }}"
                    class="{{ Request::url() === url('/') . '/dashboard' ? 'active' : '' }}"><i><svg
                            xmlns="http://www.w3.org/2000/svg" id="Dashboard_Icon" data-name="Dashboard Icon"
                            width="22" height="22" viewBox="0 0 32 32">
                            <rect id="bound" width="32" height="32" fill="none" />
                            <rect id="Rectangle_62_Copy" data-name="Rectangle 62 Copy" width="4" height="18"
                                rx="1.5" transform="translate(16 5)" />
                            <rect id="Rectangle_62_Copy_2" data-name="Rectangle 62 Copy 2" width="4" height="11"
                                rx="1.5" transform="translate(9 12)" />
                            <path id="Path_95" data-name="Path 95"
                                d="M2.667,21.333h20a1.333,1.333,0,0,1,0,2.667H1.333A1.333,1.333,0,0,1,0,22.667V1.333a1.333,1.333,0,0,1,2.667,0Z"
                                transform="translate(4 4)" />
                            <rect id="Rectangle_62_Copy_4" data-name="Rectangle 62 Copy 4" width="4" height="8"
                                rx="1.5" transform="translate(23 15)" />
                        </svg></i>Dashboard</a>
            </li>
            <li>
                <a href="{{ route('userTraining.index') }}"
                    class="{{ Request::url() === url('/') . '/my-trainings' ? 'active' : '' }}"><i>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 32 23.146">
                            <g id="_7d0d907872d693932cfeef7a6baeee16" data-name="7d0d907872d693932cfeef7a6baeee16"
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
            <li>
                <a href="{{ route('userTest.index') }}"
                    class="{{ Request::url() === url('/') . '/my-test' ? 'active' : '' }}"><i>
                        <svg width="22" height="22" viewBox="0 0 18 21" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_29_116)">
                                <path
                                    d="M3 19H11V21H3C2.20435 21 1.44129 20.6839 0.87868 20.1213C0.316071 19.5587 0 18.7956 0 18V2C0 1.20435 0.316071 0.441289 0.87868 -0.12132C1.44129 -0.68393 2.20435 -1 3 -1H13C13.7956 -1 14.5587 -0.68393 15.1213 -0.12132C15.6839 0.441289 16 1.20435 16 2V6.06H14V2C14 1.73478 13.8946 1.48043 13.7071 1.29289C13.5196 1.10536 13.2652 1 13 1H12C12 2.06087 11.5786 3.07828 10.8284 3.82843C10.0783 4.57857 9.06087 5 8 5C6.93913 5 5.92172 4.57857 5.17157 3.82843C4.42143 3.07828 4 2.06087 4 1H3C2.73478 1 2.48043 1.10536 2.29289 1.29289C2.10536 1.48043 2 1.73478 2 2V18C2 18.2652 2.10536 18.5196 2.29289 18.7071C2.48043 18.8946 2.73478 19 3 19ZM4 9H11V7H4V9ZM4 13H11V11H4V13ZM4 17H11V15H4V17ZM15 7C14.2044 7 13.4413 7.31607 12.8787 7.87868C12.3161 8.44129 12 9.20435 12 10V18C11.9992 18.1316 12.0245 18.2621 12.0742 18.3839C12.124 18.5057 12.1973 18.6166 12.29 18.71L14.29 20.71C14.383 20.8037 14.4936 20.8781 14.6154 20.9289C14.7373 20.9797 14.868 21.0058 15 21.0058C15.132 21.0058 15.2627 20.9797 15.3846 20.9289C15.5064 20.8781 15.617 20.8037 15.71 20.71L17.71 18.71C17.8027 18.6166 17.876 18.5057 17.9258 18.3839C17.9755 18.2621 18.0008 18.1316 18 18V10C18 9.20435 17.6839 8.44129 17.1213 7.87868C16.5587 7.31607 15.7956 7 15 7Z" />
                            </g>
                            <defs>
                                <clipPath id="clip0_29_116">
                                    <rect width="18" height="21" />
                                </clipPath>
                            </defs>
                        </svg>
                    </i>Test</a>
            </li>
            <li>
                <a href="{{ route('userReport.index') }}"
                    class="{{ Request::url() === url('/') . '/my-report' ? 'active' : '' }}"><i>

                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 20">
                            <path id="_0af5248c7abc1cf410fbd2458b209a04" data-name="0af5248c7abc1cf410fbd2458b209a04"
                                d="M15,8h4.4L14,2.6V7A.945.945,0,0,0,15,8Zm0,2a2.946,2.946,0,0,1-3-3V2H7A2.946,2.946,0,0,0,4,5V19a2.946,2.946,0,0,0,3,3H17a2.946,2.946,0,0,0,3-3V10ZM9,8h1a.945.945,0,0,1,1,1,.945.945,0,0,1-1,1H9A.945.945,0,0,1,8,9,.945.945,0,0,1,9,8Zm6.8,5.6-2.3,3a1.075,1.075,0,0,1-1.4.2H12l-.9-.8L9.8,17.7a1.075,1.075,0,0,1-1.4.2,1.075,1.075,0,0,1-.2-1.4h0l2-2.5a1.612,1.612,0,0,1,.7-.4,1.33,1.33,0,0,1,.8.3l.9.8,1.7-2.2a1.075,1.075,0,0,1,1.4-.2A1.033,1.033,0,0,1,15.8,13.6Z"
                                transform="translate(-4 -2)" />
                        </svg>

                    </i>Report</a>
            </li>

        </ul>
    </div>
    <div class="logoutbox dropup"> <!-- Use dropup instead of dropdown -->
        <a href="#" class="profile-box d-flex align-items-center justify-content-between text-decoration-none"
            id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="d-flex align-items-center">
                @if (!empty(Auth::user()->image))
                    <img src="{{ Auth::user()->image }}" alt="Profile" class="rounded-circle me-2" width="36"
                        height="36">
                @else
                    @php
                        $first_letter = strtoupper(substr(Auth::user()->fullname, 0, 1));
                        $space_index = strpos(Auth::user()->fullname, ' ');
                        $second_letter =
                            $space_index !== false
                                ? strtoupper(substr(Auth::user()->fullname, $space_index + 1, 1))
                                : '';
                    @endphp
                    <div class="employee-initials rounded-circle me-2">
                        {{ $first_letter . $second_letter }}
                    </div>
                @endif
                <span
                    class="fw-semibold">{{ ucwords(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</span>
            </div>
            <i class="bi bi-caret-up-fill ms-3"></i> <!-- Gap using ms-3 -->
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-1" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item py-2 text-danger" href="{{ URL('logout') }}">🚪 Logout</a></li>
            <li><a class="dropdown-item py-2" href="{{ url('/change-password') }}">🔒 Change Password</a></li>
            <li><a class="dropdown-item py-2" href="{{ route('trainee.profile') }}">👤 My Profile</a></li>
        </ul>
    </div>


</div>
