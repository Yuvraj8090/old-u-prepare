<style>
    ul li a{
        font-size:16px !important;
    }
    .nav.child_menu li {
            padding-left: 22px !important;
    }
    /*.nav.side-menu>li.active>a {*/
    /*    background:green !important;*/
    /*}*/
    /*.nav li.current-page{*/
    /*     background:gray !important;*/
    /*}*/
</style>
@php
    $user = Auth::user();
@endphp

<div class="col-md-3 left_col menu_fixed mCustomScrollbar _mCS_1 mCS-autoHide" style="overflow: visible;">
    <div id="mCSB_1" class="mCustomScrollBox mCS-minimal mCSB_vertical mCSB_outside" style="max-height: none;" tabindex="0">
        <div id="mCSB_1_container" class="mCSB_container" style="position: relative; top: 0px; left: 0px;" dir="ltr">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ url('dashboard') }}" class="site_title"><i class="fa fa-institution"></i> <span> {{ env('APP_NAME') }}</span> </a>
                </div>

                <div class="clearfix"></div>

                <div class="profile clearfix">
                    <div >
                        <img style="width:120px" src="{{ auth()->user()->profile_image }}" alt="..." class="img-circle profile_img mCS_img_loaded">
                    </div>
                </div>

                <br>
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section active">

                        <h3>
                           Name: {{ ucfirst(auth()->user()->name) }}<br>
                            <!--Dept. : {{auth()->user()->role->department}}  <br>-->
                            <!--Desig. : {{auth()->user()->designation ?? "--"}} -->
                        </h3>
                        <ul class="nav side-menu">
                            <li>
                                <a href="{{ url('/') }}">
                                    <i class="fa fa-file"></i> Visit Website
                                </a>
                            </li>
                            <li>
                                <a href="{{ auth()->user()->role->department == 'GRIEVANCE' ? route('mis.grievance.dashboard') : url('/dashboard') }}">
                                    <i class="fa fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mis.projects') }}">
                                    <i class="fa fa-home"></i> Project Monitoring
                                </a>
                            </li>

                            @if(auth()->user()->role_id === 2 || auth()->user()->role_id == 1)
                                {{-- <li>
                                    <a href="{{ route('project.index') }}">
                                        <i class="fa fa-tasks"></i>
                                        Project Monitoring
                                    </a>
                                </li> --}}

                                <li>
                                    <a>
                                        <i class="fa fa-sitemap"></i>
                                        Project Reports
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="">
                                        <li>
                                            <a href="{{ url('home/report/project-summary-report') }}" >Summary Report</a>
                                        </li>
                                        <li>
                                            <a> Finance Report <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu" style="">
                                                <li class="sub_menu">
                                                    <a href="{{ url('home/report/project-wise-report') }}">Project Wise Report </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('home/report/duration-report') }}">Duration Wise Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('home/report/admin-expense-report') }}">Admin Expense Report</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a> Implemention Report <span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu" style="">
                                                <li class="sub_menu"><a href="{{ url('home/report/procurement-report') }}">Procurement Report</a></li>
                                                <li><a href="{{ url('home/report/contract-report') }}">Contract Report</a></li>
                                                <li><a href="{{ url('home/report/execution-report') }}">Execution Report</a></li>
                                                {{--<li><a href="{{ url('home/report/environment-social-report') }}">Environment & Social <br> Report</a></li>--}}
                                                <li>
                                                    <a href="{{ route('mis.report.env-soc', 'social') }}">Social Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('mis.report.env-soc', 'environment') }}">Environment Report</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    @if(auth()->user()->role_id == 2)
                                        <a><i class="fa fa-book"></i> Manage Projects <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" style="display: none;">
                                            <li><a href="{{ route('project.create') }}">Create </a></li>
                                            <li><a href="{{ url('manage/project/edit') }}">Edit </a></li>
                                        </ul>
                                    @else
                                        <a href="{{ url('manage/project/edit') }}">
                                            <i class="fa fa-book"></i>
                                            Manage Projects
                                        </a>
                                    @endif
                                </li>

                                {{--
                                @if(auth()->user()->role_id == 1)
                                    <li>
                                        <a href="{{ route('manage-logins.index') }}">
                                            <i class="fa fa-book"></i>
                                            Manage Logins
                                        </a>
                                    </li>
                                @endif
                                --}}
                            @endif
                            @if(auth()->user()->role->name === 'PROCUREMENT-LEVEL-TWO' || auth()->user()->role->name === 'PMU-LEVEL-ONE')
                                @if(auth()->user()->role->name === 'PROCUREMENT-LEVEL-TWO')
                                    {{-- <li>
                                        <a href="{{ url('procurement/index') }}">
                                            <i class="fa fa-tasks"></i> Project Monitoring
                                        </a>
                                    </li> --}}
                                @endif
                                <li>
                                    <a><i class="fa fa-book"></i> Logins <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li><a href="{{ route('manage-logins.create') }}">Create</a></li>
                                        <li><a href="{{ route('manage-logins.index') }}">Manage</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if(auth()->user()->role->level === 'TWO' && (!in_array(auth()->user()->role->department, ['ENVIRONMENT', 'SOCIAL'])) )
                                <li>
                                    <a href="{{ route('project.index.two') }}">
                                        <i class="fa fa-tasks"></i> Create Milestones
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="{{ url('/project/work/progress') }}">
                                        <i class="fa fa-tasks"></i>  Project Monitoring
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ url('/finance/index') }}">
                                        <i class="fa fa-tasks"></i> Office Expenses
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i>  Logins <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li>
                                            <a href="{{ route('manage-logins.create') }}">Create</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('manage-logins.index') }}">Manage</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if(auth()->user()->role->level === 'TWO' && (in_array(auth()->user()->role->department, ['ENVIRONMENT','SOCIAL'])) )
                                {{-- <li>
                                    <a href="{{ url('projects/social/environment') }}">
                                        <i class="fa fa-tasks"></i> Project Monitoring
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ url('template/index') }}">
                                        <i class="fa fa-pencil"></i> Upload Templates
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i> Manage Logins <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li>
                                            <a href="{{ route('manage-logins.create') }}">Create</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('mis.users') }}">Manage</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if( auth()->user()->role->level === 'THREE' && in_array(auth()->user()->role->department, ['USDMA-PROCUREMENT','FOREST-PROCUREMENT','RWD-PROCUREMENT','PWD-PROCUREMENT','PMU-PROCUREMENT']))
                                <li>
                                    <a href="{{ url('procurement/level/three/projects') }}">
                                        <i class="fa fa-tasks"></i>  Procurement
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('contract.index') }}">
                                        <i class="fa fa-tasks"></i> Contracts
                                    </a>
                                </li>
                            @elseif(auth()->user()->role->level === 'THREE' && in_array(auth()->user()->role->department, ['PMU','PWD','RWD','FOREST','USDMA']))
                                <li>
                                    <a href="{{ route('mis.project.tracking.progress.update', 'physical') }}">
                                        <i class="fa fa-edit"></i> Update Progress
                                            {{-- <i class="fa fa-edit"></i> Update Physical/Financial Progress --}}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mis.project.tracking.tests') }}">
                                        {{-- <i class="fa fa-edit"></i> Update Progress --}}
                                        <i class="fa fa-edit"></i> Update Quality Test Reports
                                    </a>
                                </li>
                            @elseif(auth()->user()->role->level === 'THREE' && in_array(auth()->user()->role->department, ['PMU-ENVIRONMENT','PWD-ENVIRONMENT','RWD-ENVIRONMENT','FOREST-ENVIRONMENT','USDMA-ENVIRONMENT']))
                                <li>
                                    <a href="{{ url('environment/projects/update') }}">
                                        {{-- <i class="fa fa-tasks"></i> Projects --}}
                                        <i class="fa fa-tasks"></i> Update Compliances
                                    </a>
                                </li>
                                 <li>
                                    <a>
                                        <i class="fa fa-book"></i> Manage Logins <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li>
                                            <a href="{{ route('manage-logins.create') }}">Create</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('manage-logins.index') }}">Manage</a>
                                        </li>
                                    </ul>
                                </li>
                            @elseif(auth()->user()->role->level === 'THREE' && in_array(auth()->user()->role->department,['PMU-SOCIAL','PWD-SOCIAL','RWD-SOCIAL','FOREST-SOCIAL','USDMA-SOCIAL']))
                                <li>
                                    <a href="{{ url('social/projects/update') }}">
                                        <i class="fa fa-tasks"></i> Update Compliances
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i> Manage Logins <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li>
                                            <a href="{{ route('manage-logins.create') }}">Create</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('manage-logins.index') }}">Manage</a>
                                        </li>
                                    </ul>
                                </li>
                            @elseif(auth()->user()->role->level === 'FOUR' && in_array(auth()->user()->role->name,['PMU-SOCIAL-FOUR','PWD-SOCIAL-FOUR','RWD-SOCIAL-FOUR', 'FOREST-SOCIAL-FOUR','USDMA-SOCIAL-FOUR','PMU-ENVIRONMENT-FOUR','PWD-ENVIRONMENT-FOUR','RWD-ENVIRONMENT-FOUR','FOREST-ENVIRONMENT-FOUR','USDMA-ENVIRONMENT-FOUR']))
                                <li>
                                    <a href="{{ url('four/projects') }}">
                                        <i class="fa fa-tasks"></i> Add Compliances
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-tasks"></i> View Compliances
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('template/four/index') }}">
                                        <i class="fa fa-tasks"></i> Templates
                                    </a>
                                </li>
                            @endif

                            {{--
                            @if(auth()->user()->role->department == 'GRIEVANCE')
                                <li>
                                    <a href="{{ route('mis.grievance.record') }}">
                                        <i class="fa fa-tasks"></i> Record Grievances
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mis.grievance.manage') }}">
                                        <i class="fa fa-book"></i> Manage Grievances
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('mis.grievances') }}">
                                        <i class="fa fa-book"></i> Grievances
                                    </a>
                                </li>
                            @endif
                            --}}

                            @if($user->hasPermission('grievance'))
                                <li>
                                    <a href="{{ route('mis.grievance.record') }}">
                                        <i class="fa fa-tasks"></i> Record Grievances
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mis.grievance.manage') }}">
                                        <i class="fa fa-book"></i> Manage Grievances
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('mis.grievances') }}">
                                        <i class="fa fa-book"></i> Grievances
                                    </a>
                                </li>
                            @endif

                            @if ($user->hasPermission('project_creator'))
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i>
                                        Manage Projects
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li><a href="{{ route('project.create') }}">Create </a></li>
                                        <li><a href="{{ url('manage/project/edit') }}">Edit </a></li>
                                    </ul>
                                </li>
                            @else
                                {{--
                                <li>
                                    <a href="{{ route('project.index') }}">
                                        <i class="fa fa-tasks"></i>
                                        Project Monitoring
                                    </a>
                                </li>
                                --}}
                            @endif

                            @if ($user->hasPermission('manage_logins') || auth()->user()->hasRole('Admin'))
                                <li>
                                    <a>
                                        <i class="fa fa-book"></i> Manage Logins <span class="fa fa-chevron-down"></span>
                                    </a>
                                    <ul class="nav child_menu" style="display: none;">
                                        <li>
                                            <a href="{{ route('manage-logins.create') }}">Create</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('mis.users') }}">Manage</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if ($user->hasRole('Admin') || auth()->id() === 1)
                                <li>
                                    <a href="{{ url('mis/roles-permissions') }}">
                                        <i class="fa fa-book"></i> Roles Permissions
                                    </a>
                                </li>
                            @endif

                            {{--
                            @if ($user->hasPermission('manage_permissions') || $user->hasRole('Admin'))
                                <li>
                                    <a href="{{ url('mis/users-permissions') }}">
                                        <i class="fa fa-sitemap"></i> User's Permissions
                                    </a>
                                </li>
                            @endif
                            --}}

                            @if(auth()->user()->role_id == 1)
                                <li>
                                    <a href="{{ url('web-admin') }}">
                                        <i class="fa fa-globe"></i> Website Management
                                    </a>
                                </li>
                            @endif


                            <li>
                                <a>
                                    <i class="fa fa-gear"></i>
                                    ATR Review
                                    <span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu">
                                    <li>
                                        <a href="{{ route('atr.entry.form') }}">Enter Monthly ATR</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('atr.report') }}">View Monthly ATR</a>
                                    </li>
                                </ul>
                            </li>

                            @if(auth()->user()->hasRole('Admin'))
                            <li>
                                <a>
                                    <i class="fa fa-gear"></i>
                                    PD Dashboard
                                    <span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu">
                                    <li>
                                        <a href="{{ route('mis.dashboard.pd.components') }}">Manage Components</a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <li>
                                <a>
                                    <i class="fa fa-gear"></i> Settings <span class="fa fa-chevron-down"></span>
                                </a>
                                <ul class="nav child_menu" style="">
                                    <li>
                                        <a href="{{ url('profile') }}">My Profile</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('login.change-password') }}">Change Password</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    @if(false)
                    <div class="menu_section">
                        <h3>Live On</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="">
                                    <li><a href="e_commerce.html">E-commerce</a></li>
                                    <li><a href="projects.html">Projects</a></li>
                                    <li><a href="project_detail.html">Project Detail</a></li>
                                    <li><a href="contacts.html">Contacts</a></li>
                                    <li><a href="profile.html">Profile</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="">
                                    <li><a href="page_403.html">403 Error</a></li>
                                    <li><a href="page_404.html">404 Error</a></li>
                                    <li><a href="page_500.html">500 Error</a></li>
                                    <li><a href="plain_page.html">Plain Page</a></li>
                                    <li><a href="login.html">Login Page</a></li>
                                    <li><a href="pricing_tables.html">Pricing Tables</a></li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="">
                                    <li><a href="#level1_1">Level One</a>
                                    </li>
                                    <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu" style="">
                                            <li class="sub_menu"><a href="level2.html">Level Two</a>
                                            </li>
                                            <li><a href="#level2_1">Level Two</a>
                                            </li>
                                            <li><a href="#level2_2">Level Two</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="#level1_2">Level One</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>
                        </ul>
                    </div>
                    @endif

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <!--<a data-toggle="tooltip" data-placement="top" title="" data-original-title="FullScreen">-->
                    <!--    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>-->
                    <!--</a>-->
                    <!--<a data-toggle="tooltip" data-placement="top" title="" data-original-title="Lock" aria-describedby="tooltip275186">-->
                    <!--    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>-->
                    <!--</a>-->
                    <a data-toggle="tooltip" data-placement="top" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-original-title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>
    </div>
    <div id="mCSB_1_scrollbar_vertical" class="mCSB_scrollTools mCSB_1_scrollbar mCS-minimal mCSB_scrollTools_vertical" style="display: block;">
        <div class="mCSB_draggerContainer">
            <div id="mCSB_1_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 50px; display: block; height: 52px; max-height: 178px; top: 0px;" oncontextmenu="return false;">
                <div class="mCSB_dragger_bar" style="line-height: 50px;"></div>
            </div>
            <div class="mCSB_draggerRail"></div>
        </div>
    </div>
</div>
