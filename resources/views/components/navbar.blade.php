<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{!!asset('img/logo.svg')!!}" id="brand-image"></a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        {{--Left side of bar, used for pages--}}
        <ul class="navbar-nav mr-auto">
            @foreach($pages as $page)

                @if($page->name != $app->view->getSections()['pageName']) {{-- if current page --}}
                @if($page instanceof \App\Category) {{-- if a category --}}
                    <li class="nav-item dropdown">
                @else
                    <li class="nav-item">
                @endif
                @else
                    @if($page instanceof \App\Category) {{-- if a category --}}
                        <li class="nav-item active dropdown">
                    @else
                        <li class="nav-item active">
                            @endif
                            @endif
                            {{-- content of link in bar --}}
                            @if($page instanceof \App\Category) {{-- if a category, write its pages --}}
                                <a class="nav-link dropdown-toggle" href="#"
                                   id="{{ $page->name }}Dropdown" role="button" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">{{ $page->name }}</a>
                                <div class="dropdown-menu" aria-labelledby="{{ $page->name }}Dropdown">
                                    @foreach($page->pages as $cat)
                                        <a class="dropdown-item" href="{{ url($cat->route) }}">{{ $cat->name }}</a>
                                    @endforeach
                                </div>
                            @else {{-- else, just a simple link --}}
                                <a class="nav-link" href="{{ url($page->route) }}">{{ $page->name }}</a>
                            @endif

                        </li>
                        @endforeach
        </ul>

        {{--Right side of bar, used for user and login/logout--}}
        <ul class="navbar-nav ml-auto">
            @if(Auth::check())
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> {{ Auth::user()->name }}</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ url('/myAccount') }}">My Account</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}">Log Out</a>
                    </div>
                </li>
            @else
                <li class="nav-item"><a href="{{ url('/login') }}">Login/Register</a></li>
            @endif
        </ul>
    </div>
</nav>