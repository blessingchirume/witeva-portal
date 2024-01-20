<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.head')

<body class="antialiased">
  @include('includes.nav')
  <div class="wrapper">
    <div class="content-wrapper" style="min-height: 0px !important;">
      <div class="container">
        @yield('content')
      </div>
    </div>
  </div>
  @include('includes.scripts')
</body>

</html>