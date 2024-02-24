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
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                <div class="message">

                </div>
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
                <h4 class="my-3">Add Tool:</h4>
                <form id="blog_form" action="{{ route('tool.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Name</label>
                            <input type="text" name="name" class="form-control js-tool-name" placeholder="Tool Name"
                                value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Tool Slug</label>
                            <input type="text" name="slug" class="form-control js-tool-slug" placeholder="Tool Slug"
                                value="">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check ">
                                <label class="form-check-label" for="home">Is it Home?</label>
                                <input type="checkbox" class="form-check-input" name="is_home" id="home"
                                    value="1">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Meta Title"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Meta Description</label>
                            <textarea rows="3" name="meta_description" class="form-control" placeholder="Meta Title" value=""></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tool_lang" class="form-label">Language</label>
                            <select class="form-select" id="tool_lang" name="lang">
                                <option selected disabled>Select Language</option>
                                @foreach (config('constants.only_emd_languages') as $key => $value)
                                    <option value="{{ $value }}">{{ ucwords($key) }} ({{ $value }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tool_parent" class="form-label">Parent</label>
                            <select class="form-select js-tool-parent" id="tool_parent" name="parent">
                                <option selected disabled>Select Parent</option>
                                <option value="0">This is parent</option>
                                @if (isset($parent))
                                    @foreach ($parent as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <span class="js-span-toggle-content">
                        <h4 class="my-3">Content:</h4>
                        <div class="tool_content">
                            <div class="row" id="row01">
                                <input type="hidden" value="inputField" name="inputType[]">
                                <div class="col-md-3 mb-3">
                                    <input type="text" name="contentKey[]" class="form-control" placeholder="Key"
                                        value="">
                                </div>
                                <div class="col-md-9 mb-3">
                                    <input type="text" name="contentValue[]" class="form-control" placeholder="Value"
                                        value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <select class="form-select" id="add_more_type" name="add_more_type">
                                    <option selected disabled>Select Input Type</option>
                                    <option value="inputField">Input Fields</option>
                                    <option value="textarea">Text Area</option>
                                    <option value="richText">Rich Text Editor</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="#" class="btn btn-primary waves-effect waves-light" id="addMore">Add
                                    Row</a>
                            </div>
                        </div>
                    </span>
                    <div style="text-align: right">
                        @can('add_tool')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        @endcan
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>

    </div>

@endsection
@section('script')
     {{-- TINYMCE SCRIPT --}}
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script> --}}
    {{-- TINYMCE SCRIPT END --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"
        integrity="sha512-6JR4bbn8rCKvrkdoTJd/VFyXAN4CE9XMtgykPWgKiHjou56YDJxWsi90hAeMTYxNwUnKSQu9JPc3SQUg+aGCHw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('web_assets/admin/js/tinymce-script-2.js?v1.0.3') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('label').on('click', function() {
                $(this).next().focus();
            });
            $(".js-tool-parent").on("change", function() {
                let val = parseInt($(this).val());
                if (val == 0) {
                    $(".js-span-toggle-content").show();
                } else {
                    $(".js-span-toggle-content").hide();
                }
            });
            $(".js-tool-name").bind('keyup change input', function() {
                let val = $(this).val().toLowerCase();
                $('.js-tool-slug').val(val.replaceAll(" ", "-"));
            });
        });
        $("#addMore").on("click", function(e) {
            e.preventDefault();
            var selectedValue = $("#add_more_type").val();
            var html = `<div class="row"><input type="hidden" value="` + selectedValue + `" name="inputType[]">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="contentKey[]" class="form-control" placeholder="Key" value="">
                    </div>
                    <div class="col-md-9 mb-3">`;
            if (selectedValue == "inputField") {
                html +=
                    `<input type="text" name="contentValue[]" class="form-control" placeholder="Value" value="">`;
            } else if (selectedValue == "textarea") {
                html +=
                    `<textarea rows="3" name="contentValue[]" class="form-control" placeholder="Content" value=""></textarea>`;
            } else {
                html += `<input class="form-control tool_textarea" name="contentValue[]"  />`;
            }
            html += `</div></div>`;
            $(".tool_content").append(html);
            init_tinymce();
        });
    </script>
@endsection
