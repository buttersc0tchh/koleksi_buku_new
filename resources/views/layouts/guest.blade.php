<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'OLIVE BABY SHOP')</title>
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  @stack('styles')
</head>
<body>
  <div class="container-scroller">

    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
          <span class="fw-bold" style="font-size:1.1rem; color:#fff;">OLIVE BABY SHOP</span>
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
          <span class="fw-bold" style="color:#fff;">OBS</span>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <ul class="navbar-nav navbar-nav-right ms-auto">
          @auth
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="nav-profile-img">
                <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image">
                <span class="availability-status online"></span>
              </div>
              <div class="nav-profile-text">
                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
              </div>
            </a>
            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="{{ route('vendor.index') }}">
                <i class="mdi mdi-view-dashboard me-2 text-primary"></i> Dashboard
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('guest-logout-form').submit();">
                <i class="mdi mdi-logout me-2 text-primary"></i> Signout
              </a>
              <form id="guest-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
          </li>
          @else
          <li class="nav-item">
            <a class="nav-link btn btn-primary text-white px-3 my-2 me-2" href="{{ route('login') }}">
              <i class="mdi mdi-login me-1"></i> Login Admin
            </a>
          </li>
          @endauth
        </ul>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel w-100">
        <div class="content-wrapper">
          @yield('content')
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright &copy; 2026 OLIVE BABY SHOP</span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/js/misc.js') }}"></script>
  @stack('scripts')
</body>
</html>
