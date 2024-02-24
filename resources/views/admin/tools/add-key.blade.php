@extends('admin')
@section('head')
    <style>

    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="card">
            <h3>Add Key in ({{ @$tool->name }}) Language ({{ @$tool->lang }})</h3>
            @if (session()->has('message'))
                <p>{{ session('message') }}</p>
            @endif
            <div class=" tool_content_row">
                <form action="{{ route('tool.create_tool_key', ['id' => $tool->id, 'type' => 'inputField']) }}" method="POST"
                    class="row">
                    @csrf
                    <div class="col-md-2 mb-3">
                        <label for="">Key</label>
                        <input type="text" name="contentKey" class="form-control" placeholder="Key" value=""
                            required="">
                    </div>
                    <div class="col-md-7 mb-3">
                        <label for="">Value</label>
                        <input type="text" name="contentValue" class="form-control" placeholder="Value" value=""
                            required="">
                    </div>
                    <div class="col-md-2">
                        @can('edit_tool')
                            <button type="submit" class="btn btn-success">Add Key</button>
                        @endcan
                    </div>
                </form>
            </div>
            <div class=" tool_content_row">
                <form action="{{ route('tool.create_tool_key', ['id' => $tool->id, 'type' => 'textarea']) }}" method="POST"
                    class="row">
                    @csrf
                    <div class="col-md-2 mb-3">
                        <label for="">Key</label>
                        <input type="text" name="contentKey" class="form-control" placeholder="Key" value=""
                            required="">
                    </div>
                    <div class="col-md-7 mb-3">
                        <label for="">Value</label>
                        <textarea rows="3" name="contentValue" class="form-control" placeholder="Content" value="" required=""></textarea>
                    </div>
                    <div class="col-md-2">
                        @can('edit_tool')
                            <button type="submit" class="btn btn-success">Add Key</button>
                        @endcan
                    </div>
                </form>
            </div>

            <div class=" tool_content_row">
                <form action="{{ route('tool.create_tool_key', ['id' => $tool->id, 'type' => 'richText']) }}" method="POST"
                    class="row">
                    @csrf
                    <div class="col-md-2 mb-3">
                        <label for="">Key</label>
                        <input type="text" name="contentKey" class="form-control" placeholder="Key" value=""
                            required="">
                    </div>
                    <div class="col-md-7 mb-3">
                        <label for="">Value</label>
                        <input type="text" class="form-control tool_textarea" name="contentValue" />
                    </div>
                    <div class="col-md-2">
                        @can('edit_tool')
                            <button type="submit" class="btn btn-success">Add Key</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- TINYMCE SCRIPT --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script>
    {{-- TINYMCE SCRIPT END --}}
    <script src="{{ asset('web_assets/admin/js/tinymce-script.js?v1.0.2') }}"></script>
@endsection
