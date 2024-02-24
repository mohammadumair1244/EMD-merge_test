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
                    <h4 class="header-title">Custom Pages List</h4>
                    <table id="tools_table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Page Key</th>
                                <th>Blade File</th>
                                <th>Meta Title</th>
                                <th>Meta Desc</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($custom_pages as $item)
                                <tr
                                    class="{{ View::exists('layout.frontend.pages.emd-custom-pages.' . $item->blade_file) ?: 'blade_file_not' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @can('custom_page_edit')
                                            <a href="{{ route('custom_page.edit_page', ['id' => $item->id]) }}">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            {{ $item->name }}
                                        @endcan
                                    </td>
                                    <td>{{ @$item->slug }}</td>
                                    <td>{{ @$item->page_key }}</td>
                                    <td>{{ @$item->blade_file }}</td>
                                    <td>{{ @$item->meta_title }}</td>
                                    <td>{{ @$item->meta_description }}</td>
                                    <td>
                                        @can('custom_page_delete')
                                            <a class="btn btn-danger waves-effect waves-light"
                                                href="{{ route('custom_page.destroy', ['id' => $item->id]) }}">
                                                Trash
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
