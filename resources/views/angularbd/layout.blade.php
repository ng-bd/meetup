<!DOCTYPE html>
<html class="">
<head>

    @include('angularbd.partials.header')
</head>
<body>
    @yield('main-content')

    @include('angularbd.partials.footer-js')
    @include('sweetalert::alert')
</body>
</html>
