<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ $title }}</title>

    @include('admin.layout.header-link')

    @yield('css')

</head>
<body>
    <div class="admin-container">
        @include('admin.layout.header')

        @yield('body')

        @include('admin.layout.footer')
    </div>

    @include('admin.layout.footer-link')

    @yield('js')

</body>
</html>
