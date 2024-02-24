@extends('main')
@section('content')
    <section class="banner">
        <div class="container privacy-policy">
            {!! get_setting_by_key('privacy-policy')->value !!}
            <p>Puede cambiar su configuración de privacidad haciendo clic en el siguiente botón:
                <button onclick="adconsent('showGUI')">Gestionar el consentimiento</button>.
            </p>
            {!! get_setting_by_key('privacy-policy-2')->value !!}
        </div>
    </section>
@endsection
