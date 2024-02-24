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

        .tool_file_not {
            /* color: red; */
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Trash View Component</h4>
                    <table id="tools_table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Key</th>
                                <th>Parent</th>
                                <th>Language</th>
                                <th>Restore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emd_components as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ @$item->key }}</td>
                                    <td>{{ @$item->self_parent->name }}</td>
                                    <td>
                                        {{ array_search($item->lang, config('constants.only_emd_languages')) }}
                                        /
                                        {{ $item->lang }}
                                    </td>
                                    <td>
                                        @can('restore_component')
                                            <a class="btn btn-primary"
                                                href="{{ route('component.restore_link', ['id' => $item]) }}">
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
