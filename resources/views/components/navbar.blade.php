<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{!!asset('img/logo.svg')!!}" id="brand-image"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @foreach($pages as $page)
                {{--If active page, add active class, if a category, add dropdown class--}}
                <li class="nav-item {{ $page->name == $app->view->getSections()['pageName'] ? 'active' : ''}}
                {{ $page instanceof \App\Category ? 'dropdown' : '' }}">
                    {{--if page is a category--}}
                    @if($page instanceof \App\Category)
                    <a class="nav-link dropdown-toggle" href="#"
                       id="{{ $page->name }}Dropdown" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ $page->name }}</a>
                    <div class="dropdown-menu" aria-labelledby="{{ $page->name }}Dropdown">
                        @foreach($page->pages as $cat)
                            <a class="dropdown-item" href="{{ url($cat->route) }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                    @else  {{--else, just a simple link--}}
                    <a class="nav-link" href="{{ url($page->route) }}">{{ $page->name }}</a>
                    @endif

                </li>
            @endforeach
        </ul>
        <ul class="navbar-nav ml-auto">
            @if(Auth::check())
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
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