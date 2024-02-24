@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        tbody tr td {
            vertical-align: middle;
        }

        .margin_left {
            margin-left: 20px;
        }

        .field_label {
            color: #008000bf;
        }

        .json_block {
            width: 90%;
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #8080805e;
            padding: 10px;
            border-radius: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Tool Audit Table</h4>

                    <table id="audit_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($audits as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ App\Models\User::find(@$item->user_id)?->name }}</td>
                                    <td>{{ Str::upper($item->event) }}</td>
                                    <td>
                                        <div class="json_block">
                                            @php
                                                $value1 = json_decode(json_encode(@$item->old_values), true);
                                            @endphp
                                            @foreach (json_decode(json_encode(@$value1), true) as $key110 => $value110)
                                                @if (@$key110 != 'tool_content' && @$key110 != 'content')
                                                    <b class="field_label">{{ @$key110 }}</b> :
                                                    <span class="margin_left">{{ @$value110 }}</span>
                                                    <br>
                                                @endif
                                            @endforeach
                                            @if (gettype(@$value1['tool_content']) == 'string' || gettype(@$value1['content']) == 'string')
                                                @php
                                                    if (gettype(@$value1['tool_content']) == 'string') {
                                                        $value1_content = @$value1['tool_content'];
                                                    } else {
                                                        $value1_content = @$value1['content'];
                                                    }
                                                    
                                                @endphp
                                                @foreach (json_decode(@$value1_content, true) as $key2 => $value2)
                                                    <b class="field_label">{{ @$key2 }}</b> : <br>
                                                    @foreach (json_decode(json_encode(@$value2), true) as $key3 => $value3)
                                                        <b class="margin_left">{{ @$key3 }}</b> :
                                                        <span class="margin_left">{{ @$value3 }}</span>
                                                        <br>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="json_block">
                                            @php
                                                $value11 = json_decode(json_encode(@$item->new_values), true);
                                            @endphp
                                            @foreach (json_decode(json_encode(@$value11), true) as $key220 => $value220)
                                                @if (@$key220 != 'tool_content' && @$key220 != 'content')
                                                    <b class="field_label">{{ @$key220 }}</b> :
                                                    <span class="margin_left">{{ @$value220 }}</span>
                                                    <br>
                                                @endif
                                            @endforeach
                                            @if (gettype(@$value11['tool_content']) == 'string' || gettype(@$value11['content']) == 'string')
                                                @php
                                                    if (gettype(@$value11['tool_content']) == 'string') {
                                                        $value11_content = @$value11['tool_content'];
                                                    } else {
                                                        $value11_content = @$value11['content'];
                                                    }
                                                    
                                                @endphp
                                                @foreach (json_decode(@$value11_content, true) as $key22 => $value22)
                                                    <b class="field_label">{{ @$key22 }}</b> : <br>
                                                    @foreach (json_decode(json_encode(@$value22), true) as $key33 => $value33)
                                                        <b class="margin_left">{{ @$key33 }}</b> :
                                                        <span class="margin_left">{{ @$value33 }}</span>
                                                        <br>
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item['updated_at'])->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            $("#audit_table").dataTable();
        });
    </script>
@endsection
