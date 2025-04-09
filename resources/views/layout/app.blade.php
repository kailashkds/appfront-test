<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @include('layout.header-link')

    @yield('css')

</head>
<body>
    <div class="container">
        @include('layout.header')

        @yield('body')

        @include('layout.footer')
    </div>

    @include('layout.footer-link')

    @yield('js')

</body>
</html>
