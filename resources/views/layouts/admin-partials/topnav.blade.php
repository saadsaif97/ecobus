<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
   <a class="navbar-brand" href="index.html">Start Bootstrap</a>
   <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#!"><i
         class="fas fa-bars"></i></button>
   <!-- Navbar Search-->
   <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
         <input class="form-control" type="text" placeholder="Search for..." aria-label="Search"
            aria-describedby="basic-addon2" />
         <div class="input-group-append">
            <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
         </div>
      </div>
   </form>
   <!-- Navbar-->
   <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown">
         <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
         </a>

         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                           document.getElementById('logout-form').submit();">
               {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
               @csrf
            </form>
         </div>
      </li>
   </ul>
</nav>
