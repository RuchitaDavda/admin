<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ url('home') }}"><img src="{{ url('assets/images/logo/logo.png') }}" alt="Logo"
                            srcset=""></a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @if (has_permissions('read', 'dashboard'))
                    <li class="sidebar-item">
                        <a href="{{ url('home') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'categories') || has_permissions('read', 'bedroom'))
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-hexagon-fill"></i>
                            <span>Master</span>
                        </a>
                        <ul class="submenu" style="padding-left: 0rem">
                            @if (has_permissions('read', 'type'))
                                <li class="submenu-item">
                                    <a href="{{ url('housetype') }}">House Type</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'unit'))
                                <li class="submenu-item">
                                    <a href="{{ url('measurement') }}">Unit of Area</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'categories'))
                                <li class="submenu-item">
                                    <a href="{{ url('categories') }}">Categories</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permissions('read', 'customer'))
                    <li class="sidebar-item">
                        <a href="{{ url('customer') }}" class='sidebar-link'>
                            <i class="bi bi-person-circle"></i>
                            <span>Customer</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'property'))
                    <li class="sidebar-item">
                        <a href="{{ url('property') }}" class='sidebar-link'>
                            <i class="bi bi-building"></i>
                            <span>Property</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'customer'))
                    <li class="sidebar-item">
                        <a href="{{ url('property-inquiry') }}" class='sidebar-link'>
                            <i class="bi bi-question-square"></i>
                            <span>Property Inquiry</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'slider'))
                    <li class="sidebar-item">
                        <a href="{{ url('slider') }}" class='sidebar-link'>
                            <i class="bi bi-sliders"></i>
                            <span>Slider</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'notification'))
                    <li class="sidebar-item">
                        <a href="{{ url('notification') }}" class='sidebar-link'>
                            <i class="bi bi-bell"></i>
                            <span>Notification</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'users_accounts') ||
                    has_permissions('read', 'about_us') ||
                    has_permissions('read', 'privacy_policy') ||
                    has_permissions('read', 'terms_condition'))
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="submenu" style="padding-left: 0rem">
                            @if (has_permissions('read', 'users_accounts'))
                                <li class="submenu-item">
                                    <a href="{{ url('users') }}">Users Accounts</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'about_us'))
                                <li class="submenu-item">
                                    <a href="{{ url('about-us') }}">About Us</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'privacy_policy'))
                                <li class="submenu-item">
                                    <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'terms_condition'))
                                <li class="submenu-item">
                                    <a href="{{ url('terms-conditions') }}">Terms & Condition</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'system_settings'))
                                <li class="submenu-item">
                                    <a href="{{ url('system-settings') }}">System Settings</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
