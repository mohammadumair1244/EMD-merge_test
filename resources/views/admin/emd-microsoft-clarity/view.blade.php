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
                <div class="row">
                    <div class="col-md-4">
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
                        <h4 class="my-3">Add Clarity for Pages</h4>
                        <form id="blog_form" action="{{ route('clarity.add_req') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Clarity Show Percentage</label>
                                    <input type="number" min="1" max="100" name="show_percentage"
                                        class="form-control" placeholder="Clarity Show Percentage" value="">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label">Select Filed Type</label>
                                    <select name="field_type" id="" class="form-control field_type">
                                        <option value="all_pages">All Pages</option>
                                        <option value="tool_pages">Tool Pages</option>
                                        <option value="custom_pages">Custom Pages</option>
                                        <option value="single_page">Single Page</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3 js_tool_or_custom_page d-none">
                                    <label for="" class="form-label">Select Tool / Custom Page</label>
                                    <select name="tool_or_custom_page" id=""
                                        class="form-control tool_or_custom_page">
                                        <option value="tool">Tool</option>
                                        <option value="custom_page">Custom Page</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3 js_tool d-none">
                                    <label for="" class="form-label">Select Tool</label>
                                    <select name="tool_id" id="" class="form-control">
                                        @foreach ($tools as $tool)
                                            <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3 js_emd_custom_page d-none">
                                    <label for="" class="form-label">Select Custom Page</label>
                                    <select name="emd_custom_page_id" id="" class="form-control">
                                        @foreach ($emd_custom_pages as $emd_custom_page)
                                            <option value="{{ $emd_custom_page->id }}">{{ $emd_custom_page->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    @foreach ($availabilities as $availability)
                                        <div class="row">
                                            <div class="col-md-6">
                                                {{ $availability }}</div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="keys[]" value="{{ $availability }}">
                                                <select name="default_values[]" class="form-control" id="">
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                            <hr>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div style="">
                                @can('add_microsoft_clarity')
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Submit
                                    </button>
                                @endcan
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <h4 class="header-title">Clarity Page Wise List</h4>
                        <table id="tools_table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Tool Pages</th>
                                    <th>Custom Pages</th>
                                    <th>Single Tool</th>
                                    <th>Single Custom</th>
                                    <th>Percentage</th>
                                    <th>Show</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($emd_microsoft_clarity as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ @$item->is_tool_pages ? 'Yes' : 'No' }}</td>
                                        <td>{{ @$item->is_custom_pages ? 'Yes' : 'No' }}</td>
                                        <td>{{ @$item->tool->name ?: 'No' }}</td>
                                        <td>{{ @$item->emd_custom_page->name ?: 'No' }}</td>
                                        <td>{{ @$item->show_percentage }}%</td>
                                        <td>
                                            @if (@$item->clarity_json)
                                                @foreach (json_decode(@$item->clarity_json, true) as $key => $value)
                                                    {{ $key }} :
                                                    {{ $value ? 'Yes' : 'No' }}
                                                    <br>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @can('delete_microsoft_clarity')
                                                <a class="btn btn-danger waves-effect waves-light"
                                                    href="{{ route('clarity.delete_link', ['id' => $item->id]) }}">
                                                    Trash
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- end card-body -->
        </div>

    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".field_type").change(function() {
                var val = $(this).val();
                if (val == "single_page") {
                    $(".js_tool_or_custom_page").removeClass("d-none");
                    $(".js_tool").removeClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                } else {
                    $(".js_tool_or_custom_page").addClass("d-none");
                    $(".js_tool").addClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                }
            });
            $(".tool_or_custom_page").change(function() {
                var val1 = $(this).val();
                if (val1 == "tool") {
                    $(".js_tool").removeClass("d-none");
                    $(".js_emd_custom_page").addClass("d-none");
                } else {
                    $(".js_tool").addClass("d-none");
                    $(".js_emd_custom_page").removeClass("d-none");
                }
            });
        });
    </script>
@endsection
