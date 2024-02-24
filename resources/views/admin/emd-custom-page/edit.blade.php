@extends('admin')
@section('head')
    <style>
        .tox .tox-notification--warn,
        .tox .tox-notification--warning {
            display: none !important;
        }

        .images_row {
            background: transparent;
            row-gap: 0.5em;
        }

        .images_row div.image-box {
            background: gainsboro;
        }

        .img-fluid.rounded {
            height: 150px;
            object-fit: contain;
            width: 100%;
            background: #8080802e;
            padding: 5px;
        }

        .feature_img_preview img {
            max-width: 300px;
            width: 100%;
            object-fit: contain;
        }

        .row {
            position: relative;
        }

        .cross {
            position: absolute;
            cursor: pointer;
        }

        .tool_content_row::before {
            content: "";
            height: 20px;
            width: 30px;
            position: absolute;
            background-size: 20px 20px;
            background-repeat: no-repeat;
            left: -10px;
            top: 8px;
            cursor: pointer;
            background-image: url({{ asset('web_assets/admin/images/drag.png') }});
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="card">
            <div class="card-body pt-0">
                <div class="mt-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="pb-0 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <h4 class="my-3">Update Custom Page:</h4>
                <form id="blog_form" action="{{ route('custom_page.update_page', ['id' => $custom_page->id]) }}"
                    method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Page Name"
                                value="{{ $custom_page->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Blade File</label>
                            <input type="text" name="blade_file" class="form-control" disabled="" readonly=""
                                placeholder="Blade File" value="{{ $custom_page->blade_file }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Slug</label>
                            <input type="text" name="slug" class="form-control" disabled="" readonly=""
                                placeholder="Page Slug" value="{{ $custom_page->slug }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Key</label>
                            <input type="text" name="page_key" class="form-control" disabled="" readonly=""
                                value="{{ $custom_page->page_key }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
                                value="{!! $custom_page->meta_title !!}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Description</label>
                            <textarea rows="3" name="meta_description" class="form-control" placeholder="Meta Description">{!! $custom_page->meta_description !!}</textarea>
                        </div>
                    </div>
                    <h4 class="my-3 d-flex justify-content-between align-items-center">
                        Content:
                        <span>
                            @if (request()->has('v'))
                                <a href="{{ request()->url() }}" class="btn btn-sm btn-info">Normal Edit</a>
                            @else
                                <a href="{{ request()->url() }}?v=sort" class="btn btn-sm btn-danger">Deep Edit</a>
                            @endif
                        </span>
                    </h4>
                    <div class="tool_content" id="tool_content">
                        @forelse (json_decode($custom_page->content) as $key => $value)
                            <div class="row tool_content_row" data-id="{{ $key }}">
                                {{-- <span class="cross">&times;</span> --}}
                                <input type="hidden" value="{{ $value->type }}" name="inputType[]"
                                    class="taget_input_type">
                                <div class="col-md-2 mb-3">
                                    <input type="text" name="contentKey[]" class="form-control" placeholder="Key"
                                        value="{{ $key }}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    @if (request()->has('v'))
                                        <select id="{{ $key }}-input-option"
                                            data-target="{{ $key }}-input-option"
                                            class="form-control js-input-type" data-original-type="{{ $value->type }}">
                                            <option selected value="inputField"
                                                @if ($value->type == 'inputField') selected @endif>
                                                Input Fields
                                            </option>
                                            <option value="textarea" @if ($value->type == 'textarea') selected @endif>
                                                Text Area
                                            </option>
                                            <option value="richText" @if ($value->type == 'richText') selected @endif>
                                                Rich Text Editor
                                            </option>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3 input-value">
                                    @if ($value->type == 'inputField')
                                        <input type="text" name="contentValue[]" class="form-control input-to-target"
                                            placeholder="Value" value="{{ $value->value }}" id="{{ $key }}">
                                    @elseif ($value->type == 'textarea')
                                        <textarea rows="3" name="contentValue[]" class="form-control input-to-target" placeholder="Content"
                                            id="{{ $key }}">{{ $value->value }}</textarea>
                                    @else
                                        <input type="text" class="form-control tool_textarea input-to-target"
                                            value="{{ $value->value }}" name="contentValue[]"
                                            id="{{ $key }}" />
                                    @endif
                                </div>
                                <div class="col-sm-1">
                                    <img class="cross" src="{{ asset('web_assets/admin/images/remove.png') }}"
                                        alt="">
                                </div>
                            </div>
                        @empty
                        @endforelse

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <select class="form-select" id="add_more_type" name="add_more_type">
                                <option disabled>Select Input Type</option>
                                <option selected value="inputField">Input Fields</option>
                                <option value="textarea">Text Area</option>
                                <option value="richText">Rich Text Editor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="#" class="btn btn-primary waves-effect waves-light" id="addMore">Add Row</a>
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('custom_page_edit')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Update
                            </button>
                        @endcan
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>
    </div>
    @if (request()->has('v'))
        <div class="card">
            <div class="row">
                <div class="col-6 text-center pt-5">
                    <h4>Download content as json file</h4>
                    <a href="{{ route('custom_page.download_content', ['id' => $custom_page->id]) }}"
                        class="btn btn-lg btn-outline-dark">Download <b>.json</b> file</a>
                    <p class="px-5 mt-2">To use this conetnt just open the downloded file and copy the content in it and
                        paste
                        that content on right form and click on "Upload JSON" button</p>
                </div>
                <div class="col-6 pt-2">
                    <div class="message">
                    </div>
                    <form id="jsonform" action="{{ route('custom_page.upload_content', ['id' => $custom_page->id]) }}"
                        method="post">
                        @csrf
                        <textarea class="form-control" name="upload_json" id="upload_json" cols="30" rows="10"
                            placeholder="Your JSON here"></textarea>
                        <div class="my-3">
                            <div class="" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio1"
                                    autocomplete="off" value="1">
                                <label class="btn btn-outline-dark" for="btnradio1">Merge json</label>
                                <input type="radio" class="btn-check" name="btnradio" id="btnradio2"
                                    autocomplete="off" checked value="2">
                                <label class="btn btn-outline-dark" for="btnradio2">Relace whole json</label>
                            </div>
                        </div>
                        <div class="text-center">
                            @can('custom_page_edit')
                                <button type="button" class="btn btn-danger my-2" id="upload-json-submit">Upload
                                    JSON</button>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    {{-- MODAL --}}
    <div>
        <button type="button" class="btn btn-primary d-none" id="alertModalBtn" data-bs-toggle="modal"
            data-bs-target="#alert-modal">Alert</button>
        <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="alertModalLabel">Alert</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-0">
                        <hr>
                        <img class="img-fluid" src="{{ asset('web_assets/admin/images/html_to_text.png') }}"
                            alt="html_to_text">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light modal_close_btn " data-bs-dismiss="modal"
                            data-target="">Close</button>
                        <button type="button" data-target="" data-bs-dismiss="modal"
                            class="btn btn-success  confirm_convert">Confirm</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
    @if (isset($images))
        <x-admin.gallary :images="$images"></x-admin.gallary>
    @endif
    {{-- MODAL END --}}
@endsection
@section('script')
    {{-- TINYMCE SCRIPT --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script>
    {{-- TINYMCE SCRIPT END --}}
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".confirm_convert").on('click', function() {
                convert_input_type($(this).attr('data-target'));
                $('#alert-modal').modal('toggle');
            });
            $(".modal_close_btn").on('click', function() {
                let id = $(this).attr('data-target');
                let element = $("#" + id);
                element.val(element.attr('data-original-type'));
            });
            $(".js-input-type").on("change", function() {
                var target = $(this).attr('data-target');
                $(".modal_close_btn").attr('data-target', target);
                if ($(this).attr('data-original-type') == 'richText') {
                    $(".confirm_convert").attr('data-target', target);
                    $("#alertModalBtn").click();
                } else {
                    convert_input_type(target);
                }
                return;
            });
            $(document).on('click', '.cross', function() {
                if (confirm("Are you Sure you want to delete this element?")) {
                    $(this).parents('.tool_content_row').remove();
                }
            });
            $("#addMore").on("click", function(e) {
                e.preventDefault();
                var selectedValue = $("#add_more_type").val();
                var html = `<div class="row tool_content_row">
                <input type="hidden" value="` + selectedValue + `" name="inputType[]" class="taget_input_type">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="contentKey[]" class="form-control" placeholder="Key" value="">
                    </div>
                    <div class="col-md-8 mb-3">`;
                if (selectedValue == "inputField") {
                    html +=
                        `<input type="text" name="contentValue[]" class="form-control" placeholder="Value" value="">`;
                } else if (selectedValue == "textarea") {
                    html +=
                        `<textarea rows="3" name="contentValue[]" class="form-control" placeholder="Content" value=""></textarea>`;
                } else {
                    html +=
                        `<input type="text" class="form-control tool_textarea" name="contentValue[]"  />`;
                }
                html += `</div><div class="col-sm-1">
                                        <img class="cross" src="{{ asset('web_assets/admin/images/remove.png') }}" alt="">
                                    </div></div>`;
                $(".tool_content").append(html);
                init_tinymce();
            });
            $("#upload-json-submit").on("click", function(e) {
                var json = $("#upload_json").val();
                try {
                    JSON.parse(json);
                } catch (e) {
                    alert(e);
                    return false;
                }
                if (json != "") {
                    var count = Object.keys(JSON.parse(json)).length;
                    if (count < 1) {
                        alert("Cannot upload empty JSON");
                        return;
                    } else {
                        if (confirm("Are you sure?")) {
                            $("#jsonform").submit();
                        }
                    }
                } else {
                    alert("upload json");
                    return;
                }
            });
        });

        function convert_input_type(id) {
            var element = $("#" + id);
            let val = element.val();
            let parent = element.closest('.row');
            let parent_id = parent.data('id');
            let original_input = parent.find('.input-to-target');
            let input_val = original_input.val();

            let html = "";
            if (element.attr('data-original-type') == 'richText') {
                input_val = tinymce.get(parent_id).getContent({
                    format: "text"
                });
            }
            input_val = stripHtml(input_val);
            if (val == 'inputField') {
                html =
                    "<input type='text' name='contentValue[]' class='form-control input-to-target' placeholder='Value' value='" +
                    input_val + "'>";
            } else if (val == 'textarea') {
                html =
                    '<textarea rows="3" name="contentValue[]" class="form-control input-to-target" placeholder="Content">' +
                    input_val + '</textarea>';

            } else if (val == 'richText') {
                html =
                    "<input type='text' id='" + parent_id +
                    "' name='contentValue[]' class='form-control tool_textarea input-to-target' placeholder='Value' value='" +
                    input_val + "'>";
            }
            parent.find(".input-value").html(html);
            init_tinymce();
            element.attr('data-original-type', val);
            let inputType = parent.find('.taget_input_type');
            inputType.val(element.attr('data-original-type'));
        }
    </script>
    <script src="{{ asset('web_assets/admin/js/tinymce-script.js?v1.0.2') }}"></script>
    @if (request()->has('v'))
        <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
        <script defer src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        @php
            $sort = request()->input('v');
        @endphp
        @if ($sort == 'sort')
            <script>
                $(document).ready(function() {
                    $("#tool_content").sortable({
                        axis: 'y',
                        start: function(evt, ui) {
                            let editor_id = ui.item.find('.tool_textarea').attr('id');
                            if (typeof editor_id != "undefined") {
                                tinymce.execCommand('mceRemoveEditor', true, editor_id);
                            }
                        },
                        stop: function(evt, ui) {
                            let editor_id = ui.item.find('.tool_textarea').attr('id');
                            if (typeof editor_id != "undefined") {
                                tinymce.execCommand('mceAddEditor', true, editor_id);
                            }
                        }
                    });
                });
            </script>
        @endif
    @endif
@endsection
