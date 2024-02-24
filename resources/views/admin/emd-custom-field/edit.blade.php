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
                <h4 class="my-3">Edit Custom Field:</h4>
                <form id="blog_form" action="{{ route('custom_field.edit_req', ['id' => $emd_custom_field->id]) }}"
                    method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control js_name" placeholder="Name"
                                value="{{ @$emd_custom_field->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Key</label>
                            <input type="text" name="key" class="form-control js_key" placeholder="Key"
                                value="{{ @$emd_custom_field->key }}" readonly="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description"
                                value="{{ @$emd_custom_field->description }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Default Value</label>
                            <input type="number" min="0" name="default_val" class="form-control"
                                placeholder="Default Value" value="{{ @$emd_custom_field->default_val }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Select Filed Type</label>
                            <select name="field_type" id="" class="form-control field_type">
                                <option value="all_pages" {{ $emd_custom_field->is_all_pages ? 'selected' : '' }}>All Pages
                                </option>
                                <option value="tool_pages" {{ $emd_custom_field->is_tool_pages ? 'selected' : '' }}>Tool
                                    Pages</option>
                                <option value="custom_pages" {{ $emd_custom_field->is_custom_pages ? 'selected' : '' }}>
                                    Custom Pages</option>
                                <option value="single_page"
                                    {{ $emd_custom_field->tool_id || $emd_custom_field->emd_custom_page_id ? 'selected' : '' }}>
                                    Single Page</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 js_tool_or_custom_page d-none">
                            <label for="" class="form-label">Select Tool / Custom Page</label>
                            <select name="tool_or_custom_page" id="" class="form-control tool_or_custom_page">
                                <option value="tool" {{ $emd_custom_field->tool_id > 0 ? 'selected' : '' }}>Tool</option>
                                <option value="custom_page"
                                    {{ $emd_custom_field->emd_custom_page_id > 0 ? 'selected' : '' }}>Custom Page</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 js_tool d-none">
                            <label for="" class="form-label">Select Tool</label>
                            <select name="tool_id" id="" class="form-control">
                                @foreach ($tools as $tool)
                                    <option value="{{ $tool->id }}"
                                        {{ $emd_custom_field->tool_id == $tool->id ? 'selected' : '' }}>
                                        {{ $tool->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 js_emd_custom_page d-none">
                            <label for="" class="form-label">Select Custom Page</label>
                            <select name="emd_custom_page_id" id="" class="form-control">
                                @foreach ($emd_custom_pages as $emd_custom_page)
                                    <option value="{{ $emd_custom_page->id }}"
                                        {{ $emd_custom_field->emd_custom_page_id == $emd_custom_page->id ? 'selected' : '' }}>
                                        {{ $emd_custom_page->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('edit_custom_field')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Update
                            </button>
                        @endcan
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>

    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            function field_type() {
                var val = $(".field_type").val();
                if (val == "single_page") {
                    $(".js_tool_or_custom_page").removeClass("d-none");
                    $(".js_tool").removeClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                    tool_or_custom_page();
                } else {
                    $(".js_tool_or_custom_page").addClass("d-none");
                    $(".js_tool").addClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                }

            }
            field_type();
            $(".field_type").change(function() {
                field_type();
            });

            function tool_or_custom_page() {
                var val1 = $(".tool_or_custom_page").val();
                if (val1 == "tool") {
                    $(".js_tool").removeClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                } else {
                    $(".js_tool").addClass("d-none");
                    $(".js_emd_custom_page").removeClass("d-none");
                }
            }

            $(".tool_or_custom_page").change(function() {
                tool_or_custom_page();
            });
            $(".js_name").bind('keyup change input', function() {
                let val = $(this).val().toLowerCase().trim();
                $('.js_key').val(val.replaceAll(" ", "_"));
            });
        });
    </script>
@endsection
