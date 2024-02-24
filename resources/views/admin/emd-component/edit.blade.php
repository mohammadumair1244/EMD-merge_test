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
                <h4 class="my-3">Edit Component of ({{ @$emd_component?->name ?: '' }})</h4>
                <form id="blog_form" action="{{ route('component.edit_req', ['id' => request()->route('id') ?? 0]) }}"
                    method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control js_name" placeholder="Name"
                                value="{{ $emd_component->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Key</label>
                            <input type="text" name="key" class="form-control js_key" placeholder="Key"
                                value="{{ $emd_component->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-select" name="lang">
                                <option selected disabled>Select Language</option>
                                @foreach (config('constants.only_emd_languages') as $key => $value)
                                    <option value="{{ $value }}"
                                        {{ $value == $emd_component->lang ? 'selected' : '' }}>{{ ucwords($key) }}
                                        ({{ $value }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <span class="js-span-toggle-content">
                        <h4 class="my-3">Content:</h4>
                        <div class="tool_content">
                            @forelse (json_decode($emd_component->json_body) as $key => $value)
                                <div class="row tool_content_row" data-id="{{ $key }}">
                                    {{-- <span class="cross">&times;</span> --}}
                                    <input type="hidden" value="{{ $value->type }}" name="inputType[]"
                                        class="taget_input_type">
                                    <div class="col-md-2 mb-3">
                                        <input type="text" name="contentKey[]" class="form-control" placeholder="Key"
                                            value="{{ $key }}">
                                    </div>
                                    <div class="col-md-2 mb-3">
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
                            @endforeach
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
                        @can('edit_component')
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
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin">
    </script>
    {{-- TINYMCE SCRIPT END --}}
    <script src="{{ asset('web_assets/admin/js/tinymce-script.js?v1.0.2') }}"></script>
    <script>
        $(document).ready(function() {
            $('label').on('click', function() {
                $(this).next().focus();
            });
            $(".js_name").bind('keyup change input', function() {
                let val = $(this).val().toLowerCase();
                $('.js_key').val(val.replaceAll(" ", "_"));
            });
        });
        $(document).on('click', '.cross', function() {
            if (confirm("Are you Sure you want to delete this element?")) {
                $(this).parents('.tool_content_row').remove();
            }
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
