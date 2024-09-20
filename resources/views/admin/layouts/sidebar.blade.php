<div class="wrapper">
    <div class="app-sidebar menu-fixed" data-background-color="man-of-steel" data-image="{{ asset('app-assets/img/sidebar-bg/01.jpg') }}" data-scroll-to-active="true">
        <div class="sidebar-header">
            <div class="logo clearfix">
                <a class="logo-text float-left" href="{{ route('admin.dashboard.index') }}">
                    <div class="logo-img">
                        <img src="{{ asset('admin_auth/images/img-01.png') }}" alt="Hotel" style="width: 140px; height: 50px; margin-left: 25px;" />
                    </div>
                </a>
            </div>
        </div>
        <div class="sidebar-content main-menu-content">
            <div class="nav-container">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <!-- Dashboard Item -->
                    <li class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard.index') }}">
                            <i class="ft-home"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    <!-- Hotel Item -->
                    <li class="nav-item {{ request()->is('admin/hotels*') ? 'active' : '' }}">
                        <a href="{{ route('admin.hotels.index') }}">
                            <i class="ft-home"></i>
                            <span class="menu-title">Hotels</span>
                        </a>
                    </li>

                    <!-- Room -->
                    <li class="nav-item {{ request()->is('admin/rooms*') ? 'active' : '' }}">
                        <a href="{{ route('admin.rooms.index') }}">
                            <i class="ft-home"></i>
                            <span class="menu-title">Rooms</span>
                        </a>
                    </li>

                    <!-- Booking -->
                    <li class="nav-item {{ request()->is('admin/bookings*') ? 'active' : '' }}">
                        <a href="{{ route('admin.bookings.index') }}">
                            <i class="ft-home"></i>
                            <span class="menu-title">Booking</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
