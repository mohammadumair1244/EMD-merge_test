<header>
    <div class="container">
        <div class="navbar-wrapper border-bottom">
            <div class="logo-wrapper">
                <p style="display: flex;gap: 7px;align-items:center">
                    <img src="{{ asset('web_assets/frontend/img/header_icon.svg') }}" alt="header_icon" style="width: 30px;height: auto;">
                    <a href="/">PARAFRASEO</a>
                </p>
                <span class="toggle-btn"><img src="{{ asset('web_assets/frontend/img/toogle.svg') }}"
                        alt="toogle"></span>
            </div>
            <div class="navbar">
                <nav>
                    <ul>
                        {{-- <li class="nav-link"><a href="{{ route('home') }}">PARAFRASEO</a></li> --}}
                        <li class="nav-link"><a href="{{ route('page.blog') }}">BLOG</a></li>
                        {{-- <li class="nav-link"><a href="{{ route('page.blog') }}">BLOG</a></li> --}}
                        <li class="nav-link"><a class="contact_head" href="{{ route('contact_us') }}">CONTACTO</a></li>
                    </ul>
                </nav>
            </div>
            
        </div>
    </div>
</header>
