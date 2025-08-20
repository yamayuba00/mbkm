<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.partials.head')
</head>
<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      @include('layouts.partials.navbar')
      @include('layouts.partials.sidebar')

      <div class="main-content">
        @yield('content')
      </div>

      @include('layouts.partials.footer')
    </div>
  </div>

  @include('layouts.partials.scripts')
</body>
</html>
