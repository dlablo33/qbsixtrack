<!DOCTYPE html>
<html lang="en">
    @include('layouts.header')
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            @include('layouts.left-nav')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
            @yield('content')
            </div>

            @include('layouts.footer')
            @include('sweetalert::alert')
    </body>
</html>
