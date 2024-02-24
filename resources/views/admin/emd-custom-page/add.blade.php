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
                <h4 class="my-3">Add Custom Page:</h4>
                <form id="blog_form" action="{{ route('custom_page.create_page') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Page Name"
                                value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Blade File</label>
                            <input type="text" name="blade_file" class="form-control" placeholder="Blade File Name"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Slug (<del>https://www.domainname.com/</del>testing/pricing-plan)</label>
                            <input type="text" name="slug" class="form-control js-custom-page-slug" placeholder="Page Slug"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Page Key</label>
                            <input type="text" name="page_key" readonly="" class="form-control js-page-key" placeholder="Page Key"
                                value="">
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
                        @can('custom_page_add')
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('label').on('click', function() {
                $(this).next().focus();
            });
            $(".js-custom-page-slug").bind('keyup change input', function() {
                let val = $(this).val().toLowerCase().trim();
                val=val.replaceAll(" ","-");
                $('.js-page-key').val(val.replaceAll("/", "-"));
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
