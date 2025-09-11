<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <div class="logo-box">
                <a class='logo logo-dark' href='#'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/img/logo_sm.webp') }}" alt="" height="45">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/img/logo.webp') }}" alt="" height="45">
                    </span>
                </a>
                <a class='logo logo-light' href='#'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/img/logo_sm.webp') }}" alt="" height="45" style="filter:grayscale(2) invert(1)">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/img/logo.webp') }}" alt="" height="45" style="filter:grayscale(2) invert(1)">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a class='tp-link'  href="{{ url('website-management') }}">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a class='tp-link'  href="{{ url('dashboard') }}">
                        <i data-feather="table"></i>
                        <span>MIS Dashboard </span>
                    </a>
                </li>
                <li>
                    <a class='tp-link'  href="{{ url('/') }}" target="_blank">
                        <i data-feather="globe"></i>
                        <span>Visit Website</span>
                    </a>
                </li>
                <li class="menu-title">Header</li>
                <li>
                    <a class='tp-link' href='{{ route('navigation.index') }}'>
                        <i data-feather="columns"></i>
                        <span>Navigation</span>
                    </a>
                </li>
                <li>
                    <a class='tp-link' href='{{ route('feedback.get') }}'>
                        <i data-feather="columns"></i>
                        <span>Feedback</span>
                    </a>
                </li>
                <li class="menu-title">Pages</li>
                <li>
                    <a class='tp-link' href='{{ route('page.create') }}'>
                        <i data-feather="columns"></i>
                        <span>Add Page</span>
                    </a>
                </li>
                <li>
                    <a class='tp-link' href='{{ route('page.index') }}'>
                        <i data-feather="columns"></i>
                        <span>All Page</span>
                    </a>
                </li>
                <li>
                    <a class='tp-link' href='{{ route('announcements.index') }}'>
                        <i data-feather="tv"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                <li>
                    <a href="#sidebarError" data-bs-toggle="collapse">
                        <i data-feather="alert-octagon"></i>
                        <span>Components</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarError">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="{{ route('page.edit', 'enhancing-infrastructure-resilience') }}">Enhancing Iinfrastructure Resilience</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit', 'improving-emergency-preparedness-and-response') }}'>Improving Emergency Preparedness And Response</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit', 'preventing-and-managing-forest-and-general-fires') }}'>Preventing And Managing Forest And General Fires</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit', 'project-management') }}'>Project Management</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#sidebarTables" data-bs-toggle="collapse">
                        <i data-feather="alert-octagon"></i>
                        <span>About</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href="{{ route('page.edit', 'about-us') }}">About U-Prepare</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit','mission-and-vision') }}'>Mission And Vision</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit','history') }}'>History</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit','objectives') }}'>Objectives</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{ route('page.edit','project-structure') }}'>Project Structure</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
