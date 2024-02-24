@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .feature {
            max-width: 250px;
        }

        .blade_file_not {
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Trashed Custom Fields List</h4>
                    <table id="tools_table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name / Description</th>
                                <th>Key</th>
                                <th>Default Value</th>
                                <th>All Pages</th>
                                <th>All Tool Pages</th>
                                <th>All Custom Pages</th>
                                <th>Tool</th>
                                <th>Custom Page</th>
                                <th>Restore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($custom_fields as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }} <br> {{ $item->descriptioin }}</td>
                                    <td>{{ @$item->key }}</td>
                                    <td>{{ @$item->default_val }}</td>
                                    <td>{{ @$item->is_all_pages }}</td>
                                    <td>{{ @$item->is_tool_pages }}</td>
                                    <td>{{ @$item->is_custom_pages }}</td>
                                    <td>{{ @$item->tool->name ?: 0 }}</td>
                                    <td>{{ @$item->emd_custom_page->name ?: 0 }}</td>
                                    <td>
                                        @can('restore_custom_field')
                                            <a class="btn btn-success waves-effect waves-light"
                                                href="{{ route('custom_field.restore_link', ['id' => $item->id]) }}">
                                                Restore
                                            </a>
                                        @endcan
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
            $("#tools_table").dataTable();
        });
    </script>
@endsection
