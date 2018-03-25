<html lang="{{ app()->getLocale() }}">
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Aston Events - @yield('title')</title>
</head>
<body>

@include('components.sidebar')

{{--main content of page--}}
<div class="container-fluid">
    <h1>@yield('pageName')</h1>
    @yield('content')
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>