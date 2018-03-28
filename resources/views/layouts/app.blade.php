<html lang="{{ app()->getLocale() }}">
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon"/>
    <title>Aston Events - @yield('title')</title>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js"
            integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l"
            crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js"
            integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/pwstrength-bootstrap.min.js"></script>
</head>
<body>

@include('components.navbar')

{{--main content of page--}}
<div class="container">
    <h1>@yield('pageName')</h1>
    @yield('content')
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>