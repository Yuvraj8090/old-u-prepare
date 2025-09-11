<div class="top_nav">
  <div class="nav_menu">
    <div class="nav toggle">
      <a id="menu_toggle"><i class="fa fa-bars"></i></a>
    </div>
    
    <!--<div class="navbar-brand text-center">-->
    <!--  <span class="text-dark">Department Name</span>-->
    <!--</div>-->
    
    <nav class="nav navbar-nav">
    
      <ul class="navbar-right">
        <li class="nav-item dropdown open" style="padding-left: 15px;">
          <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('asset/build/images/user-icon-jpg.jpg') }}" alt=""> <b style="font-weight:500;" >{{ ucfirst(auth()->user()->name) }} (DEPARTMENT : {{auth()->user()->role->department}})</b>
          </a>
          <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
            <!-- <a class="dropdown-item" href="javascript:;"> Profile</a> -->
            <!-- <a class="dropdown-item" href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a> -->
            <!-- <a class="dropdown-item" href="javascript:;">Help</a> -->

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>

            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fa fa-sign-out pull-right"></i> Log Out
            </a>
          </div>
        </li>
 
      </ul>
    </nav>
  </div>
</div>

