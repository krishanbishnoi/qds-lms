<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            {{-- <a href="#" class="nav-link">
          <div class="nav-profile-image">
            <img src="{{ asset('assets/images/faces/face1.jpg')}}" alt="profile">
            <span class="login-status online"></span>
            <!--change to offline or busy as needed-->
          </div>
          <div class="nav-profile-text d-flex flex-column">
            <span class="font-weight-bold mb-2">David Grey. H</span>
            <span class="text-secondary text-small">Project Manager</span>
          </div>
          <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
        </a> --}}
        </li>
        <li
            class="nav-item {{ request()->is('admin/dashboard') || request()->is('admin/dashboard/*') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        @can('user_management_access')
            <li
                class="nav-item {{ request()->is('admin/users') || request()->is('admin/users/*') || request()->is('admin/cop-admin') || request()->is('admin/cop-admin/*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-user-manage" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title">{{ trans('cruds.userManagement.title') }}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account menu-icon"></i>
                </a>
                <div class="collapse" id="ui-user-manage">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a
                                class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">{{ trans('cruds.user.title') }}</a></li>
                        {{-- <li class="nav-item"> <a class="nav-link {{ request()->is('admin/cop-admin') || request()->is('admin/cop-admin/*') ? 'active' : '' }}" href="{{ route("admin.cop-admin.index") }}">Corporate Admins</a></li> --}}
                    </ul>
                </div>
            </li>
        @endcan
        {{-- @can('training_management_access') --}}
        <li
            class="nav-item {{ request()->is('admin/trainings') || request()->is('admin/trainings/*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-user-manage" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-title">{{ trans('cruds.trainingManagement.title') }}</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
            <div class="collapse" id="ui-user-manage">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a
                            class="nav-link {{ request()->is('admin/trainings') || request()->is('admin/trainings/*') ? 'active' : '' }}"
                            href="{{ route('admin.training.index') }}">{{ trans('cruds.training.title') }}</a></li>
                </ul>
            </div>
        </li>
        {{-- @endcan --}}

        {{-- <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-feature" aria-expanded="false" aria-controls="ui-basic">
          <span class="menu-title">{{ trans('cruds.hotel.feature_manager') }}</span>
          <i class="menu-arrow"></i>
          <i class="mdi mdi-account menu-icon"></i>
        </a>
        <div class="collapse" id="ui-feature">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ route("admin.roomtype.index") }}"> {{ trans('cruds.allhotel.fields.roomtype') }}</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route("admin.bedtype.index") }}"> {{ trans('cruds.allhotel.fields.bedtype') }}</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route("admin.amenity.index") }}">  {{ trans('cruds.allhotel.fields.amenities') }}</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ route("admin.policy.index") }}">  {{ trans('cruds.allhotel.fields.policy') }}</a></li>
          </ul>
        </div>
      </li> --}}

        @can('contacts_access')
            <li
                class="nav-item {{ request()->is('admin/contacts') || request()->is('admin/contacts/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                    <span class="menu-title"> {{ trans('cruds.contact.title') }}</span>
                    <i class="mdi mdi-contact-mail menu-icon"></i>
                </a>
            </li>
        @endcan
        @can('page_access')
            <li class="nav-item {{ request()->is('admin/page') || request()->is('admin/page/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.page.index') }}">
                    <span class="menu-title"> {{ trans('cruds.pages.title') }}</span>
                    <i class="mdi mdi-google-pages menu-icon"></i>
                </a>
            </li>
        @endcan


        @can('user_management_access')
            <li
                class="nav-item {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') || request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-role-manage" aria-expanded="false"
                    aria-controls="ui-basic">
                    <span class="menu-title"> {{ trans('cruds.roles&permissions.title') }}</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account-check menu-icon"></i>
                </a>
                <div class="collapse" id="ui-role-manage">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a
                                class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}"
                                href="{{ route('admin.permissions.index') }}"> {{ trans('cruds.permission.title') }}</a>
                        </li>
                        <li class="nav-item"> <a
                                class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}"
                                href="{{ route('admin.roles.index') }}">{{ trans('cruds.role.title') }}</a></li>
                    </ul>
                </div>
            </li>
        @endcan


        <li
            class="nav-item {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-setting-manage" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-title"> {{ trans('cruds.settings.title') }}</span>
                <i class="menu-arrow"></i>
                <i class="mdi  mdi-settings menu-icon"></i>
            </a>
            <div class="collapse" id="ui-setting-manage">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a
                            class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.index') }}">
                            {{ trans('cruds.settings.fields.general') }}</a></li>
                    <li class="nav-item"> <a
                            class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.social-settings') }}"> Social Settings</a></li>
                </ul>
            </div>
        </li>


        @can('user_management_access')
            <li class="nav-item {{ request()->is('admin/mails') || request()->is('admin/mails/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.mails.index') }}">
                    <span class="menu-title"> {{ trans('cruds.mailtemplate.title') }}</span>
                    <i class="mdi mdi-contact-mail menu-icon"></i>
                </a>
            </li>
        @endcan

        @can('user_management_access')
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="logout()">
                    <span class="menu-title"> {{ trans('global.logout') }}</span>
                    <i class="mdi mdi-logout-variant menu-icon"></i>
                </a>
            </li>
        @endcan



    </ul>
</nav>
