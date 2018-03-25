<div class="sidebar">
    <h1>@yield('pageName')</h1>

    @foreach($pages as $page)

        @if($page->name != $app->view->getSections()['pageName'])
            <div><a href='{{ url($page->route) }}'>{{ $page->name }}</a></div>
        @endif
    @endforeach

</div>