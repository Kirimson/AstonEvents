<div class="sidebar">
    <h1>@yield('pageName')</h1>

    @foreach($pages as $page)

        @if($page != $app->view->getSections()['pageName'])
            <div><a href='{{ url('/list') }}'>{{ $page }}</a></div>
        @endif
    @endforeach

</div>