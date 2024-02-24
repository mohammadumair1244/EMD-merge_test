@extends('admin')
@section('head')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Availability in this Transaction</h4>
                    <table id="users_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Tool</th>
                                <th>Query Limit</th>
                                <th>Query Used</th>
                                <th>Allow JSON</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emd_user_transaction_allows as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item->tool->name ?: 'All Tools' }}</td>
                                    <td>{{ @$item->queries_limit }}</td>
                                    <td>{{ @$item->queries_used }}</td>
                                    <td>
                                        @if (@$item->allow_json)
                                            @foreach (json_decode(@$item->allow_json, true) as $key => $value)
                                                {{ $key }} :
                                                {{ $value }}
                                                <br>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            @endforelse

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
            $("#users_table").dataTable();
        });
    </script>
@endsection
