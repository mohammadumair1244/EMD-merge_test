@extends('admin')
@section('head')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('error'))
                <p class="alert alert-blue">{{ session()->get('error') }}</p>
            @endif
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Allow Query Limits in ({{ @$plan_name }}) Plan</h3>
                @can('add_pricing_plan')
                    <span class="btn btn-link text-blue" ype="button" id="addQueryBtn" data-bs-toggle="modal"
                        data-bs-target="#add-query">+ Add Quries</span>
                @endcan
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table emd-table1">
                        <tr>
                            <th>Sr</th>
                            <th>Selected Tool</th>
                            <th>Queries</th>
                            <th>Custom Fields</th>
                            <th class="actioncell"></th>
                        </tr>
                        @forelse ($emd_pricing_plan_allows as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @can('edit_pricing_plan')
                                        <a
                                            href="{{ route('emd_edit_pricing_plan_allow', ['plan_id' => $item->emd_pricing_plan_id ?? 0, 'id' => $item->id]) }}">
                                            {{ @$item->tool->name ?? 'All Web Tools' }}
                                        </a>
                                    @else
                                        {{ @$item->tool->name ?? 'All Web Tools' }}
                                    @endcan
                                </td>
                                <td>{{ $item->queries_limit == 1 ? 'Unlimited' : ($item->queries_limit ?: 'None') }}</td>
                                <td class="custom-fields">
                                    @if (@$item->allow_json)
                                        @foreach (json_decode(@$item->allow_json, true) as $key => $value)
                                            <span> {{ $key }} : {{ $value }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @can('delete_pricing_plan')
                                        <form
                                            action="{{ route('emd_destroy_pricing_plan_allow', ['plan_id' => $item->emd_pricing_plan_id ?? 0, 'id' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger table-btn">TRASH</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                        @endforelse

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD QUERY MODAL -->
    <div id="add-query" class="modal fade show" tabindex="-1" aria-labelledby="AddQueryLabel" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Queries</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </button>
                </div>
                <form action="{{ route('emd_create_pricing_plan_allow', ['plan_id' => request()->route('plan_id')]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body add-query-body pb-0">
                        <h4>Select Page</h4>
                        <div class="d-flex gap-2 mt-1">
                            <label class="emd-radio-btn btn btn-outline-dark" for="option1">
                                All Tools
                                <input class="js_all_tools js_select_option" type="radio" name="tool_id" value="0"
                                    id="option1">
                            </label>
                            <label class="emd-radio-btn btn btn-outline-dark p-0 d-inline-block" for="option3">
                                <input class="valueInput js_select_option js_specific_tool" type="radio" name="tool_id"
                                    value="0" id="option3">
                                <span class="dropdown" id="select-tool-page-dropdown">
                                    <span class="dropdown-toggle-tool d-inline-block" data-bs-toggle="dropdown"
                                        href="#" role="button" aria-haspopup="false" aria-expanded="true"
                                        style="padding:.45rem .9rem">
                                        Tool Specific
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown" style="">
                                        <span class="dropdown-item dropdown-item-tool p-0">
                                            <input class="form-control search-tool-page-input" placeholder="page title"
                                                type="text">
                                        </span>
                                        @foreach ($tools as $tool)
                                            <span class="dropdown-item dropdown-item-tool option"
                                                data-value="{{ $tool->id }}">{{ $tool->name }}</span>
                                        @endforeach
                                    </div>
                                </span>

                            </label>
                        </div>
                        <h4 class="mt-3">Queries</h4>
                        <div class="query-section mt-1">
                            <div class="emd-radio-btn-group">
                                <label for="" class="emd-radio-btn active js_query" id="0">
                                    None
                                    <input type="radio" name="query" checked>
                                </label>
                                <label for="" class="emd-radio-btn js_query" id="1">
                                    Unlimited
                                    <input type="radio" name="query">
                                </label>
                                <label for="" class="emd-radio-btn js_query" id="2">
                                    Custom
                                    <input type="radio" name="query">
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="number" min="0" value="0" name="queries_limit"
                                    class="form-control js_queries_limit" placeholder="Limit" readonly="">
                            </div>
                        </div>

                        <h4 class="mt-3">Custom Fields</h4>
                        <div style="max-height: 300px; overflow-y: scroll;">
                            <table class="table custom-fields-table show_data_table">
                            </table>
                        </div>

                    </div>
                    @can('add_pricing_plan')
                        <div class="modal-footer pt-0">
                            <button type="submit" class="btn btn-primary">Save Values</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".js_select_option").click(function() {
                var tool_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('custom_field.get_key_tool_filter') }}",
                    data: {
                        tool_id: tool_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        var html_content = '';
                        response.data.forEach(element => {
                            html_content += `<tr>
                                <td>${element.name} <br> ${element.description}</td>
                                <td class="text-right">
                                    <input type="hidden" name="keys[]" value="${element.key}">
                                    <input class="form-control" type="number" name="default_values[]" min="0" value="${element.default_val}">
                                </td>
                            </tr>`;
                        });
                        $(".show_data_table").html(html_content);
                    }
                });
            });

            $(".js_query").click(function() {
                // Get the value of the selected radio input
                var selectedValue = parseInt($(this).attr('id'));
                $(".js_queries_limit").attr("readonly", true);
                if (selectedValue == 0) {
                    $(".js_queries_limit").val(0);
                } else if (selectedValue == 1) {
                    $(".js_queries_limit").val(1);
                } else {
                    $(".js_queries_limit").val(0);
                    $(".js_queries_limit").removeAttr("readonly");
                }
            });

            // Query Limit

            // $('#add-query').modal('show');
            $(".emd-radio-btn").click(function(e) {
                $(this).addClass("active").siblings("label").removeClass("active");
            })

            $('#select-tool-page-dropdown').on('shown.bs.dropdown', function() {
                var a = $(this).find("input").focus();
            });

            $(".search-tool-page-input").on("input", function(e) {
                var value = $(this).val().toLowerCase();
                $("#select-tool-page-dropdown .dropdown-item.option").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });



            $(".dropdown-item-tool.option").on("click", function(e) {
                var text = $(this).text();
                $(".dropdown-toggle-tool").text(text);
                value = $(this).data("value");
                $(this).parents(".emd-radio-btn").find(".valueInput").val(value);

            });

            $(".js_all_tools").click(function() {
                $(".js_specific_tool").val(0);
                $(".dropdown-toggle-tool").text("Tool Specific");
            });

        });
    </script>
@endsection
