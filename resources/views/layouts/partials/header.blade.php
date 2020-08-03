<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/home')}}" class="nav-link">Home</a>
    </li>

    {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
    </li> --}}
</ul>




<!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link h4" data-toggle="dropdown" href="#" aria-expanded="true" >
          <i class="fas fa-cogs"></i>
          {{-- <span class="badge badge-warning navbar-badge">15</span> --}}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="{{route('profile.index')}}" class="dropdown-item">
              <i class="fas fa-cog mr-2 text-primary"></i> Profile
              {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
            </a>
            <div class="dropdown-divider"></div>
                <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                <i class="fas fa-power-off mr-2 text-danger"></i> Log out
                {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
            </a>
            {{-- <div class="dropdown-divider"></div> --}}

        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
            <i class="fas fa-th-large"></i>
        </a>
    </li>
</ul>
