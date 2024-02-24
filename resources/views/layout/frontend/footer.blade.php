<footer>
    <div class="container">
        <div class="grid-container border-top" style="grid-template-rows:auto; grid-template-columns:auto;">
            <div class="item1 footer_para">
                <h3>Parafraseo</h3>

                <p>
                    {!! get_setting_by_key('footer_logo_des')->value ?? '' !!}
                </p>
               
            </div>

            <div class="item3">
                <div class="grid-item3a">
                    <h4>
                        Company
                    </h4>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">
                                Hogar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('page.blog') }}">
                                Blog
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact_us') }}">
                                Contacto nos
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="grid-item3a">
                    <h4>
                        ABOUT
                    </h4>
                    <ul>
                        <li>
                            <a href="{{ route('contact_us') }}">
                                Contacto
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('privacy_policy') }}">
                                Política de Privacidad
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms_and_conditions') }}">
                                Términos y condiciones
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- <div class="grid-item3a">
                    <h4>
                        JOINED WITH US
                    </h4>
                    <ul>
                        <li>
                            <a>
                                Address: Hr tower, kohinoor city, Faisalabad
                            </a>
                        </li>
                        <li>
                            <a>
                                Phone: +1 (516) 243-7385
                            </a>
                        </li>
                        <li>
                            <a>
                                Email: Help@Parafraseo.net
                            </a>
                        </li>
                    </ul>
                </div> --}}
            
            </div>
        </div>
        <div class="footer-flex">
            <a class="d-flex-c" href="https://www.facebook.com/parafraseo.net/" target="_blank">
                <img src="{{ asset('web_assets/frontend/img/facebook.png') }}" alt="facebook social icon">
            </a>
            <a class="d-flex-c" href="#">
                <img src="{{ asset('web_assets/frontend/img/instagram.png') }}" alt="instagram social icon">
            </a>
            <a class="d-flex-c" href="https://www.linkedin.com/company/parafraseo/" target="_blank">
                <img src="{{ asset('web_assets/frontend/img/linkedin.png') }}" alt="linkedin social icon">
            </a>
            <a class="d-flex-c" href="#">
                <img src="{{ asset('web_assets/frontend/img/twitter_update.svg') }}" alt="twitter social icon">
            </a>
        </div>
    </div>
</footer>
<section class="copyright_section">
    <div class="container">
        <div class="copyright">
            <p>
                © {{ \Carbon\Carbon::parse('Y')->format('Y') }} Copyright Por <b> Parafraseo.Net </b> | Reservados todos los derechos
            </p>
        </div>
    </div>
</section>

<script src="{{ asset('web_assets/frontend/script/jquery.min.js') }}"></script>
<script src="{{ asset('web_assets/frontend/script/script.js?v=' . config('constants.product_version') . '') }}">
<script src="https://www.rewordingtool.io/web_assets/frontend/script/jquery_qtip.js?v=1.3"></script>
<script src="{{ asset('web_assets/frontend/script/jspdf.min.js?v=1.0') }}"></script>
