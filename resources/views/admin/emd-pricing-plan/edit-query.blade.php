@extends('admin')
@section('head')
    <style>
        .js_delete_btn {
            color: red;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Query Limit</h4>
                    <form
                        action="{{ route('emd_update_pricing_plan_allow', ['plan_id' => request()->route('plan_id'), 'id' => request()->route('id')]) }}"
                        method="POST">
                        @csrf
                        <label>Select Tool</label>
                        <select name="tool_id" id="" class="form-control js_change_tool" required="">
                            <option value="0" {{ $emd_pricing_plan_allow->tool_id == 0 ? 'selected' : 'disabled' }}>All
                                Tools</option>
                            @foreach ($tools as $tool)
                                <option value="{{ $tool->id }}"
                                    {{ $emd_pricing_plan_allow->tool_id == $tool->id ? 'selected' : 'disabled' }}>
                                    {{ $tool->name }}</option>
                            @endforeach
                        </select>
                        <label for="">Query Limit</label>
                        <input type="number" min="0" placeholder="Query Limit" class="form-control"
                            name="queries_limit" value="{{ $emd_pricing_plan_allow->queries_limit }}" required>
                        <br>
                        <div class="row">
                            <div class="col-md-6"><b>Key</b></div>
                            <div class="col-md-6"><b>Default Value</b></div>
                        </div>
                        <hr>
                        <div class="row">
                            @if (@$emd_pricing_plan_allow->allow_json)
                                @foreach (json_decode(@$emd_pricing_plan_allow->allow_json, true) as $key => $value)
                                    <div class="col-md-6 old_keys delete_key{{ $loop->iteration }}">
                                        {{ $key }}</div>
                                    <div class="col-md-4 delete_key{{ $loop->iteration }}">
                                        <input type="hidden" name="keys[]" value="{{ $key }}">
                                        <input type="number" name="default_values[]" min="0"
                                            value="{{ $value }}" class="form-control">
                                    </div>
                                    <div class="col-md-2  delete_key{{ $loop->iteration }}">
                                        <span class="js_delete_btn" id="{{ $loop->iteration }}">Remove</span>
                                    </div>
                                    <hr class="delete_key{{ $loop->iteration }}">
                                @endforeach
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6"><b>Name / Description</b></div>
                            <div class="col-md-6"><b>Default Value</b></div>
                        </div>
                        <div class="show_data row">
                        </div>
                        <br>
                        @can('edit_pricing_plan')
                            <button type="submit" class="btn btn-success">Save</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var old_key_array = [];

            function change_old_key() {
                $(".old_keys").each(function(index, element) {
                    old_key_array.push(($(element).text().replace(/\n/g, '')).trim());
                });
                change_tool();
            }
            change_old_key();

            function change_tool() {
                var tool_id = $(".js_change_tool").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('custom_field.get_key_tool_filter') }}",
                    data: {
                        tool_id: tool_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        var html_content = '';
                        var index_start = old_key_array.length;
                        response.data.forEach(element => {
                            if (!old_key_array.includes(element.key)) {
                                ++index_start;
                                html_content += `<div class="col-md-6 delete_key${index_start}">${element.name} <br> ${element.description}</div>
                            <div class="col-md-4 delete_key${index_start}">
                                <input type="hidden" name="keys[]" value="${element.key}">
                                <input type="number" name="default_values[]" min="0" value="${element.default_val}" class="form-control">
                            </div>
                            <div class="col-md-2 delete_key${index_start}">
                                <span class="js_delete_btn" id="${index_start}">Remove</span>
                            </div>
                            <hr class="delete_key${index_start}">`;
                            }
                        });
                        $(".show_data").html(html_content);
                    }
                });
            }

            $(".js_change_tool").change(function() {
                change_tool();
            });
            $(document).on("click", ".js_delete_btn", function() {
                var del_id = $(this).attr('id');
                $(".delete_key" + del_id).remove();
            });
        });
    </script>
@endsection
