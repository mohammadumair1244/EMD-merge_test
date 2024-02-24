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
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Blog Data Table</h4>

                    <table id="blog_table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Title</th>
                                <th>Feature Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($blogs))
                                @foreach ($blogs as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @php
                                                $path = App\Models\Media::get_column($item->img_id, 'path');
                                            @endphp
                                            <img class="feature" src="{{ asset("$path") }}" alt="image">
                                        </td>
                                        <td>
                                            @can('blog_restore')
                                                <a class="btn btn-success"
                                                    href="{{ route('blog.restore', ['id' => $item->id]) }}">
                                                    RESTORE
                                                </a>
                                            @endcan
                                            @can('blog_delete')
                                                <a class="btn btn-danger"
                                                    href="{{ route('blog.permanent_destroy', ['id' => $item->id]) }}">
                                                    DESTROY
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
            $("#blog_table").dataTable();
        });
    </script>
@endsection
