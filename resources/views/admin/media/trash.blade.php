@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .feature {
            max-width: 250px;
            width: 60px;
            border-radius: 50%;
        }

        tbody tr td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Trash Media Table</h4>

                    <table id="media_table" class="table ">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Dimension</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($media))
                                @foreach ($media as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                <img src="{{ asset($item->path) }}" width="50" height="50"
                                                    alt="">
                                            </div>
                                        </td>
                                        <td><strong>{{ $item->dimension }}</strong></td>
                                        <td>
                                            <p style="max-width: 200px;overflow: hidden;">{{ asset($item->path) }}</p>
                                        </td>
                                        <td>
                                            @can('delete_media')
                                                <a href="{{ route('media.permanent_destroy', ['id' => $item->id]) }}"
                                                    class="btn btn-danger">DESTROY</a>
                                            @endcan
                                            @can('restore_media')
                                                <a href="{{ route('media.restore', ['id' => $item->id]) }}"
                                                    data-url="{{ asset($item->path) }}" class="btn btn-success">RESTORE</a>
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
            $("#media_table").dataTable();
        });
    </script>
@endsection
