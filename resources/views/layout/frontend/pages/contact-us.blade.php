@extends('main')
@section('content')
@if(session('success'))
<div class="success-message" id="success-message">
    <p>{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="error-message">
    <p>{{ session('error') }}</p>
</div>
@endif
<section class="banner">
    <div class="container contact-page">
        <div class="d-flex main-contact-div">
            <div class="form-div-input">
                <div id="loading" style="display: none;">
                    <img src="{{asset('web_assets/frontend/img/loader.gif')}}" alt="Loader" style="max-width: 250px">
                </div>
                <div class="success-message" style="display: none;">
                    <p>Submitted Successfully!</p>
                </div>
                <div class="error-message" style="display: none;">
                    <p>Submission failed. Please try again.</p>
                </div>
                <form action="" id="contactus_form">
                    <div>
                        <label for="username">Su nombre</label>
                        <input type="text" name="username" id="username" class="input-form" placeholder="Introduzca su nombre" required>
                        <div id="username-error" class="error-messages" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="email">Tu correo electrónico</label>
                        <input type="text" name="email" id="email" class="input-form" placeholder="Introduce tu correo electrónico" required>
                        <div id="email-error" class="error-messages" style="display: none;"></div>
                    </div>
                    <div>
                        <label for="message">Mensaje</label>
                        <div class="contact-message">
                            <textarea name="input" id="input" placeholder="Ingrese su mensaje..." required></textarea>
                            <div class="tool-footer d-flex">
                                <div class="d-flex">
                                </div>
                                <!-- <button class="btn-blue" type="submit">
                                    Aplique Ahora <img src="{{ asset('web_assets/frontend/img/arrow.png?v=1')}}" alt="arrow icon">
                                </button> -->
                                <div id="jsShadowRoot">
                                </div>
                            </div>
                        </div>
                        <div class="after-input">&nbsp;</div>
                        <div id="input-error" class="error-messages" style="display: none; margin-top:16px;"></div>
                    </div>
                </form>
            </div>
            <div class="form-div-image">
                <h1>Contacto</h1>
                <p class="h1-desc">
                    Rellene el formulario y nuestro equipo se pondrá en contacto
                    A usted dentro de las 24 horas.
                </p>
                <div class="div-img">
                    <img src="{{ asset('web_assets/frontend/img/contact-page.png')}}" alt="Contact Page Image">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
