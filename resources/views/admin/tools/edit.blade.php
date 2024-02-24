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
                <h4 class="my-3">Update Tool:</h4>
                <form id="blog_form" action="{{ route('tool.update', ['tool' => $tool]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Tool Name"
                                value="{{ $tool->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Slug</label>
                            <input type="text" name="slug" class="form-control" disabled placeholder="Tool Slug"
                                value="{{ $tool->slug }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Language</label>
                            <input type="text" name="" class="form-control" disabled value="{{ $tool->lang }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Parent/Child</label>
                            <input type="text" name="" class="form-control" disabled
                                value="{{ $tool->id == $tool->parent_id ? 'Parent Tool' : ($tool->parent_id == 0 ? 'No Parent, No Child' : 'Child Tool') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
                                value="{!! $tool->meta_title !!}">
                        </div>
                        @if ($tool->parent_id == $tool->id)
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Request Limit (per mints)</label>
                                <input type="number" min="1" name="request_limit" class="form-control"
                                    placeholder="Request Limit per mints ex: 10" value="{!! @$tool->request_limit !!}" required>
                            </div>
                        @endif
                        @if ($tool->parent_id == 0 || is_null($tool->parent_id))
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Select Paraent Tool</label>
                                <select name="parent_id" id="" class="form-control">
                                    @foreach ($tools as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $tool->id ? 'selected' : '' }}>
                                            {{ $item->id == $tool->id ? 'This is Parent' : $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Description</label>
                            <textarea rows="3" name="meta_description" class="form-control" placeholder="Meta Description">{!! $tool->meta_description !!}</textarea>
                        </div>
                    </div>
                    <h4 class="my-3 d-flex justify-content-between align-items-center">
                        Content:
                        @can('edit_tool')
                            @if (@$tool->id == @$tool->parent_id)
                                <a href="{{ route('tool.add_tool_key', ['id' => @$tool->id]) }}"
                                    class="btn btn-sm btn-primary">Add More Key</a>
                            @endif
                        @endcan
                        @if (@$tool->id != @$tool->parent_id && @$tool->parent_id != 0)
                            {{-- <div class="">
                                <span class="btn btn-info get_trans_all_btn">Get
                                    Trans All Keys</span>
                                <span class="d-none waiting_for_all_trans">Waiting for All Keys Transalate from Parenet
                                    Keys</span>
                            </div> --}}
                        @endif
                        <span>
                            @if (request()->has('v'))
                                <a href="{{ request()->url() }}" class="btn btn-sm btn-info">Normal Edit</a>
                            @else
                                <a href="{{ request()->url() }}?v=sort" class="btn btn-sm btn-danger">Deep Edit</a>
                            @endif
                        </span>
                    </h4>
                    <div class="tool_content" id="tool_content">
                        @forelse (json_decode($tool->content) as $key => $value)
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
                                @if (!request()->has('v') && $tool->id != $tool->parent_id && @$tool->parent_id != 0)
                                    <div class="col-md-1">
                                        <span class="btn btn-info get_trans_btn" id="{{ $key }}">Get
                                            Trans</span>
                                    </div>
                                @endif
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
                        @can('edit_tool')
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
                    <a href="{{ route('content.download', ['tool' => $tool]) }}"
                        class="btn btn-lg btn-outline-dark">Download <b>.json</b> file</a>
                    <p class="px-5 mt-2">To use this conetnt just open the downloded file and copy the content in it and
                        paste
                        that content on right form and click on "Upload JSON" button</p>
                </div>
                <div class="col-6 pt-2">
                    <div class="message">
                    </div>
                    <form id="jsonform" action="{{ route('content.upload', ['tool' => $tool]) }}" method="post">
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
                            <button type="button" class="btn btn-danger my-2" id="upload-json-submit">Upload
                                JSON</button>
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
            $(".get_trans_btn").click(function() {
                $(".get_trans_btn").hide();
                var data_key = $(this).attr('id');
                var content_text = $("#" + data_key).val();
                var lang = "{{ @$tool->lang }}";
                $.ajax({
                    url: "{{ route('tool.key_translate') }}",
                    method: 'POST',
                    data: {
                        content_text: content_text,
                        lang: lang,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(ret) {
                        $(".get_trans_btn").show();
                        $("#" + data_key).val(ret);
                        tinymce.get(data_key).setContent(ret);
                    }
                });
            });

            $(".get_trans_all_btn").click(function() {
                var result = confirm("Are you sure you want to proceed?");
                if (!result) {
                    return false;
                }
                $(".get_trans_all_btn").hide();
                $(".waiting_for_all_trans").removeClass("d-none");
                var lang = "{{ @$tool->lang }}";
                var tool_id = "{{ @$tool->id }}";
                $.ajax({
                    url: "{{ route('tool.all_key_translate') }}",
                    method: 'POST',
                    data: {
                        tool_id: tool_id,
                        lang: lang,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(ret) {
                        window.location.reload();
                    }
                });
            });
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
