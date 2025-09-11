<nav class="navbar">
    <div class="container-xxl">
        <ul>
            <li>
                <a href="{{ route('public.page.home') }}" @class(['active' => Route::currentRouteName() == 'public.page.home'])>HOME</a>
            </li>

            @php
                $routeName = Route::currentRouteName();
                $pageName  = explode('public.page.', $routeName);
                $pageName  = count($pageName) == 2 ? $pageName[1] : NULL;
            @endphp

            <li class="dropdown">
                <a href="#" @class(['active'=> in_array($pageName, ['about', 'mission', 'history', 'objective', 'structure', 'team'])])>
                    <span>ABOUT</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('public.page.about') }}" @class(['active' => $routeName == 'public.page.about'])>About U-PREPARE</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.mission') }}" @class(['active' => $routeName == 'public.page.mission'])>Mission and Vision</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.history') }}" @class(['active' => $routeName == 'public.page.history'])>History</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.objective') }}" @class(['active' => $routeName == 'public.page.objective'])>Objectives</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.structure') }}" @class(['active' => $routeName == 'public.page.structure'])>Project Structure</a>
                    </li>
                    <li>
                        {{--<a href="{{ route('public.page.team') }}" @class(['active' => $routeName == 'public.page.team'])>Our Team</a>--}}
                    </li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" @class(['active'=> in_array($pageName, ['eninfrares', 'imempres', 'forestfire', 'projmanage', 'conemres'])])>
                    <span>COMPONENTS</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('public.page.eninfrares') }}" @class(['active' => $routeName == 'public.page.eninfrares'])>Enhancing Infrastructure Resilience</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.imempres') }}" @class(['active' => $routeName == 'public.page.imempres'])>Improving Emergency Preparedness and Response</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.forestfire') }}" @class(['active' => $routeName == 'public.page.forestfire'])>Preventing and Managing Forest and General Fires</a>
                    </li>
                    <li>
                        <a href="{{ route('public.page.projmanage') }}" @class(['active' => $routeName == 'public.page.projmanage'])>Project Management</a>
                    </li>
                    {{--
                    <li>
                        <a href="{{ route('public.page.conemres') }}" @class(['active' => $routeName == 'public.page.conemres'])>Contingent Emergency Response Component</a>
                    </li>
                     --}}
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" @class(['active' => Route::currentRouteName() == 'public.resources'])>
                    <span>RESOURCES</span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul>
                    <li>
                        <a href="#">Blogs</a>
                    </li>
                    <li>
                        <a href="#">Press releases</a>
                    </li>
                    <li>
                        <a href="#">News</a>
                    </li>
                    <li>
                        <a href="#">Gallery</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#" @class(['active' => Route::currentRouteName() == 'public.project.status'])>PROJECT STATUS</a>
            </li>

            <li class="dropdown">
                <a href="#" @class(['active'=> in_array(Route::currentRouteName(), ['public.grievance.register', 'public.grievance.status'])])>
                    <span>GRIEVANCES </span>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('public.grievance.register') }}" @class(['active' => Route::currentRouteName() == 'public.grievance.register'])>Register</a>
                    </li>
                    <li>
                        <a href="{{ route('public.grievance.status') }}"  @class(['active' => Route::currentRouteName() == 'public.grievance.status'])>Status</a>
                    </li>
                </ul>
            </li>

            <li>
                @if(auth()->guest())
                    <a href="{{ route('login') }}" @class(['active' => Route::currentRouteName() == 'mis.login'])>MIS LOGIN</a>
                @else
                    <a href="{{ route('home.index') }}">Dashboard</a>
                @endif
            </li>

            <li>
                <a href="{{ route('public.page.contact') }}" @class(['active'=> Route::currentRouteName() == 'public.page.contact'])>CONTACT US</a>
            </li>

            <li class="prel">
                <a href="#" class="search">
                    <i class="bi bi-search m-0"></i>
                </a>
                <div class="pabs sinp-box d-none">
                    <form>
                        <input class="form-control" type="text" name="search" placeholder="Search here..." >
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <i class="bi bi-list mobile-nav-toggle"></i>
</nav><!-- .navbar -->
