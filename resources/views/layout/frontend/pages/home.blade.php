@extends('main')

@section('content')
    {{-- Tool Section --}}
    <section>
        <div class="tooltip_main" style="left:200px; top:500px; display:none;" no="0">
            <ul id="list-syn" class="list-syn">
            </ul>
            <span></span>
        </div>
        <div class="container">
            <div class=" d-flex tool-section">
                <div class="tool-input" style="background: #F6F6FA;">
                    <div class="tool-input-header">
                        <div style="display: flex;">
                            <div class="modes modes2 modes1 active" onclick="activateMode(this)">
                                <img src="{{ asset('web_assets/frontend/img/simple.svg') }}" alt="Loader"
                                    class="modes-img">
                                <span class="modes-text" style="font-family: 'Roboto';">{!! $content->mode1->value !!}</span>
                            </div>
                            <div class="modes modes2 modes3" onclick="activateMode(this)">
                                <img src="{{ asset('web_assets/frontend/img/creative.svg') }}" alt="Loader"
                                    class="modes-img">
                                <span class="modes-text" style="font-family: 'Roboto';">{!! $content->mode2->value !!}</span>
                            </div>
                            <div class="modes modes3" onclick="activateMode(this)">
                                <img src="{{ asset('web_assets/frontend/img/academic.svg') }}" alt="Loader"
                                    class="modes-img">
                                <span class="modes-text" style="font-family: 'Roboto';">{!! $content->mode3->value !!}</span>
                            </div>
                        </div>
                        <div>
                            {{-- <div style="font-family: 'Roboto'">
                                <span id="total_words">
                                    0
                                </span>
                                Palabras
                            </div> --}}
                        </div>
                    </div>
                    <hr style=" border-top: 1px solid #E0E0E0;">
                    <div class="tool">
                        <form id="form">
                            <div id="loading" style="display: none;">
                                <img src="{{ asset('web_assets/frontend/img/loader.gif') }}" alt="Loader"
                                    style="max-width: 250px">
                            </div>
                            <textarea name="input" id="input" onkeyup="totwords(this)" oninput="CheckInputVal()"
                                placeholder="Escribe o pega tu texto aquí..."></textarea>
                            <div class="loader-div">
                                <img src="{{ asset('web_assets/frontend/img/loader.gif') }}" alt="Loader"
                                    style="max-width: 250px">
                            </div>
                            <div class="tool-footer d-flex">
                                <div class="d-flex">
                                    {{-- <button class="d-flex c-pointer" type="button">
                                    <img src="{{asset('web_assets/frontend/img/addicon.png')}}" alt="add icon">
                                </button> --}}
                                    <div id="footer-hide"
                                        style="
                                    display: flex;
                                    gap: 10px;
                                ">
                                        <button class="d-flex c-pointer flag-n" type="button" id="file_upload"
                                            data-title="Subir Archivo">
                                            <img src="{{ asset('web_assets/frontend/img/addicon.png') }}" alt="folder icon">
                                        </button>
                                        <span class="d-flex" id="words_count" style="font-family: 'Roboto'">
                                            Subir Archivo
                                        </span>
                                    </div>
                                    <div style="font-family: 'Roboto'; display:none;" id="total-words-count">
                                        <span id="total_words">
                                            0
                                        </span>
                                        Palabras
                                    </div>
                                </div>
                                <div id="jsShadowRoot2">
                                </div>
                            </div>
                        </form>
                        <form id="gettext-form" enctype="multipart/form-data">
                            <input type="file" accept=".txt,.doc,.docx,.pdf" name="file" id="upload_file"
                                style="display: none;">
                            <input id="upload_file_form" type="submit" style="display: none;">
                        </form>
                    </div>
                    <div class="after-input">&nbsp;</div>
                </div>
                <div class="tool-output" style="background: #F6F6FA;">
                    <div class="tool-output-header">
                        <div id="resultado" style="visibility: hidden;">RESULTADO</div>
                        {{-- <div id="palabras" style="visibility: hidden;"><span id="total_words_response">0</span> Palabras
                        </div> --}}
                    </div>

                    <hr style=" border-top: 1px solid #E0E0E0;">
                    <div class="result-sec">
                        <div class="tool tool_output">
                            <div id="output"></div>
                            <div class="tool-footer d-flex">
                                <div id="palabras" style="visibility: hidden;"><span id="total_words_response">0</span>
                                    Palabras
                                </div>
                                <div class="d-flex" id="result_tools" style="display: none;">
                                    <button class="d-flex c-pointer flag-n" data-title="Copiar"
                                        onclick="copy_result(this,'output')">
                                        <img src="{{ asset('web_assets/frontend/img/copy.png') }}" alt="copy icon">
                                    </button>
                                    <button class="d-flex c-pointer download_text">
                                        <span class="download__options">
                                            <span onclick="download_form('txt')">.txt</span>
                                            <span onclick="download_form('pdf')">.pdf</span>
                                            <span onclick="download_form('docx')">.docx</span>
                                        </span>
                                        <img src="{{ asset('web_assets/frontend/img/download.png') }}"
                                            alt="download icon">
                                    </button>
                                    <button class="d-flex c-pointer flag-n" onclick="reset_form()"
                                        data-title="Reiniciar">
                                        <img src="{{ asset('web_assets/frontend/img/reset.png') }}" alt="reset icon">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="after-output">&nbsp;</div>
                        {{-- Tooltip --}}
                        <div class="tooltip_main" style="left: 924.875px; top: 330px; width: 247px; display: none;"
                            no="3">
                            <span id="crossTip" class="btn btn-danger pull-right bt-xs"
                                style="padding:1px 7px; margin:16px -1px -43px 0 !important;float: right;">
                                X
                            </span>
                            <div class="tooltip_top"></div>
                            <div class="tooltip_innerbox">
                                <div class="loader-div-response">
                                    <img src="{{ asset('web_assets/frontend/img/loader.gif') }}" alt="Loader"
                                        style="max-width: 150px">
                                </div>
                                <strong>palabra original: </strong>
                                <span id="orgWord"><span class="word"></span> </span>
                                <br>
                                <strong>Sugerencias: </strong>
                                <span id="sugest">
                                    <span class="word"> </span>
                                    <br>
                                    <strong>O agrega tu propia palabra:</strong><br>
                                    <div class="tooltip_flex">
                                        <input type="text" class="input" id="ownword">
                                        <span class="usebtn" id="useword" tip="3">
                                            Usar
                                        </span>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Alert Box --}}
    <section>
        <div class='customAlert'>
            <img class="img_place" src="" alt="" style="width: 60%;height:40%">
            <p class='message'></p>
            <input type='button' class='confirmButton btn-blue' value='Ok'>
            <p></p>
        </div>
    </section>
    <div id="overlay"></div>
    <section>
        <div class="container">
            <div class="feature-section border-bottom">
                <h1>
                    {!! $content->our_feature_head->value !!}
                </h1>
                <p>
                    {!! $content->our_feature_des->value !!}
                </p>
            </div>
        </div>
    </section>
    <section>
        <div class="container result_alternate">
            <div class="tool-use">
                <h2>{{ $content->how_to_use_head->value }}</h2>
            </div>
            <div class="tool-instructions border-bottom">
                <div class="tool-ins-div d-flex tool-ins-div-head">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/add_lg.png') }}" alt="arrow icon">
                    </div>
                    <div>
                        <h3 class="tool-ins-div-head_bold">{!! $content->choose_file_head->value !!}</h3>
                        <p>{!! $content->choose_file_des->value !!}</p>
                    </div>
                </div>
                <div class="tool-ins-div d-flex tool-ins-div-head">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/message.png') }}" alt="arrow icon">
                    </div>
                    <div>
                        <h3 class="tool-ins-div-head_bold">{!! $content->copy_paste_head->value !!}</h3>
                        <p>{!! $content->copy_paste_des->value !!}</p>
                    </div>
                </div>
                <div class="tool-ins-div d-flex tool-ins-div-head">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/cursor.png') }}" alt="arrow icon">
                    </div>
                    <div>
                        <h3 class="tool-ins-div-head_bold">{!! $content->click_head->value !!}</h3>
                        <p>{!! $content->click_des->value !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Feature section --}}

    <section>
        <div class="container">
            <div class="feature-section border-bottom">
                <h2>
                    {!! $content->heading_2->value !!}
                </h2>
                <p>
                    {!! $content->heading_2_desc->value !!}
                </p>
            </div>
        </div>
    </section>
    <section>
        <div class="container mb-5 border-bottom" id="features">
            <div class="grid-feature">
                <div class="grid-item">

                    <div class="inline">
                        <span>1</span>
                        <h3>{!! $content->feature_1_head->value !!}</h3>
                    </div>

                    <p>
                        {!! $content->feature_1_des->value !!}
                    </p>
                </div>
                <div class="grid-item">
                    <div class="inline">
                        <span>2</span>
                        <h3>{!! $content->feature_2_head->value !!}</h3>
                    </div>
                    <p>
                        {!! $content->feature_2_des->value !!}
                    </p>
                </div>
                <div class="grid-item">
                    <div class="inline">
                        <span>3</span>
                        <h3>{!! $content->feature_3_head->value !!}</h3>
                    </div>
                    <p>
                        {!! $content->feature_3_des->value !!}
                    </p>
                </div>
                <div class="grid-item">
                    <div class="inline">
                        <span>4</span>
                        <h3>{!! $content->feature_4_head->value !!}</h3>
                    </div>
                    <p>
                        {!! $content->feature_4_des->value !!}
                    </p>
                </div>
                <div class="grid-item">
                    <div class="inline">
                        <span>5</span>
                        <h3>{!! $content->feature_head_5->value !!}</h3>
                    </div>
                    <p>
                        {!! $content->feature_des_5->value !!}
                    </p>
                </div>
                <div class="grid-item">
                    <div class="inline">
                        <span>6</span>
                        <h3>{!! $content->feature_head_6->value !!}</h3>
                    </div>
                    <p>
                        {!! $content->feature_des_6->value !!}
                    </p>
                </div>

            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="feature-section border-bottom">
                <h2>
                    {!! $content->ques_head->value !!}
                </h2>
                <p>
                </p>
                <p>
                    {!! $content->ques_des->value !!}
                </p>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="feature-section " style="padding-bottom: 0px">
                <h2>
                    {!! $content->user_head->value !!}
                </h2>
                <p>
                    {!! $content->user_des->value !!}
                </p>
                <p></p>
            </div>

    </section>
    {{-- FAQ section --}}
    <section>
        <div class="container">
            <div class="grid-container uses">
                <div class="item item-1">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/pen.svg') }}" alt="Loader">
                        <h3>
                            {!! $content->user_head_1->value !!}
                        </h3>
                    </div>
                    <p class="item-align-justify">{!! $content->user_des_1->value !!}
                    </p>
                </div>
                <div class="item item-1">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/admin.svg') }}" alt="Loader">

                        <h3>{!! $content->user_head_2->value !!}</h3>
                    </div>
                    <p class="item-align-justify">{!! $content->user_des_2->value !!}</p>
                </div>
                <div class="item item-1">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/cap.svg') }}" alt="Loader">

                        <h3>{!! $content->user_head_3->value !!}</h3>
                    </div>
                    <p class="item-align-justify">{!! $content->user_des_3->value !!}</p>
                </div>
                <div class="item ">
                    <img src="{{ asset('web_assets/frontend/img/image_feature_3.svg') }}" alt="Loader"
                        style="width: 500px;
                        max-width: 100%;">
                </div>
                <div class="item item-1">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/voice.svg') }}" alt="Loader">

                        <h3>{!! $content->user_head_4->value !!} </h3>
                    </div>
                    <p class="item-align-justify"> {!! $content->user_des_4->value !!}
                    </p>
                </div>
            </div>
        </div>

    </section>
    <section>
        <div class="container mb-5 border-bottom">
            <div class="grid-container features-para">
                <div class="item item-1">
                    <img src="{{ asset('web_assets/frontend/img/feature_2.svg') }}" alt="feature_2">
                </div>
                <div class="item">
                    <div class="grid-item">
                        <h2>{!! $content->feature_8_head->value !!}</h2>
                        <p class="item-align-justify">{!! $content->feature_8_des->value !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section>
        <div class="container blog-section border-bottom">
            <h2>{!! $content->faqs_heading->value !!}</h2>
        </div>
    </section>
    <section>
        <div class="container mb-5">
            <div class="faq-section">
                <div class="faq border-bottom ">
                    <div class="d-flex c-pointer">
                        <h3>{!! $content->faq_1_head->value !!}</h3>
                        <img src="/web_assets/frontend/img/downward_arrow.png" alt="arrow">
                    </div>
                    <p>
                        {!! $content->faq_1_des->value !!}
                    </p>
                </div>
                <div class="faq border-bottom">
                    <div class="d-flex c-pointer">
                        <h3>{!! $content->faq_2_head->value !!}</h3>
                        <img src="/web_assets/frontend/img/downward_arrow.png" alt="arrow">
                    </div>
                    <p>
                        {!! $content->faq_2_des->value !!}
                    </p>
                </div>
                <div class="faq border-bottom">
                    <div class="d-flex c-pointer">
                        <h3>{!! $content->faq_3_head->value !!}</h3>
                        <img src="/web_assets/frontend/img/downward_arrow.png" alt="arrow">
                    </div>
                    <p>
                        {!! $content->faq_3_des->value !!}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Section --}}


    {{-- <section>
        <div class="container blog-section border-bottom">
            <h2>{!! $content->blogs_head->value !!} </h2>
            <p>{!! $content->blogs_des->value !!}</p>
        </div>
    </section>
    <section>
        <div class="container blog-section">

            <div class="grid-blog">
                <div class="blog-item">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/image_5.svg') }}" alt="Blog Image">
                    </div>
                    <p>Lorem Ipsum Has Been The Industry's Standard</p>
                    <br>
                    <a href="">Learn More <img src="{{ asset('web_assets/frontend/img/more_icon.png') }}"
                            alt="more"></a>
                </div>
                <div class="blog-item">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/image_5.svg') }}" alt="Blog Image">
                    </div>
                    <p>Lorem Ipsum Has Been The Industry's Standard</p>
                    <br>
                    <a href="">Learn More <img src="{{ asset('web_assets/frontend/img/more_icon.png') }}"
                            alt="more"></a>
                </div>
                <div class="blog-item">
                    <div>
                        <img src="{{ asset('web_assets/frontend/img/image_5.svg') }}" alt="Blog Image">
                    </div>
                    <p>Lorem Ipsum Has Been The Industry's Standard</p>
                    <br>
                    <a href="">Learn More <img src="{{ asset('web_assets/frontend/img/more_icon.png') }}"
                            alt="more"></a>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- // {{-- Explore Section --}} -->

    {{-- <section>
        <div class="container">
            <div class="feature-section">
                <h2>
                    {!! $content->explore_head->value !!}
                </h2>
                <p>
                    {!! $content->explore_des->value !!}
                </p>
                <br>
                <form action="">
                    <div class="d-flex-c">
                        <input type="text" class="form-control" placeholder="{!! $content->email_placeholder->value !!}">
                        <button class="btn-blue btn-form"><span>{!! $content->email_submit_btn->value !!} </span> <img
                                src="{{ asset('web_assets/frontend/img/arrow.png') }}" alt="arrow icon">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}
@endsection
<script>
    function activateMode(element) {
        // Remove active class from all modes
        var allModes = document.querySelectorAll('.modes');
        allModes.forEach(function(mode) {
            mode.classList.remove('active');
        });

        // Add active class to the clicked mode
        element.classList.add('active');
    }
</script>
