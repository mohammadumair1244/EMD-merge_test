@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .images_row {
            background: transparent;
            row-gap: 0.5em;
        }

        .images_row div.image-box {
            background: gainsboro;
            padding: 5px
        }

        .img-fluid.rounded {
            height: 150px;
            object-fit: contain;
            width: 100%;
        }

        .d-none {
            display: none;
        }

        .position-relative {
            position: relative;
        }

        .delete {
            position: absolute;
            display: none;
            visibility: hidden;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(61, 56, 56, 0.199);
        }

        .position-relative:hover .delete {
            height: 100%;
            top: 0;
            bottom: 0;
            z-index: 999;
            visibility: visible;
        }

        i.fa-times {
            color: red;
            font-size: 30px;
        }

        i.fa-copy {
            font-size: 22px;
            color: blue;
        }

        .images_row .fa {
            margin: 0px 10px;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="card col-md-6 col-sm-6">
            <div class="card-body pt-0">
                <div class="message">

                </div>
                <h4 class="my-3">Add Media:</h4>
                <form id="media_form" action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-12 mb-3 ">
                            <label for="feature_image" class="form-label">Feature Image</label>
                            <input type="file" name="feature" id="feature_image" class="form-control">
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('add_media')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        @endcan
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Image Data Table</h4>
                    <table id="media_table" class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Dimension</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($medias as $media)
                                <tr>
                                    <td>
                                        <div>
                                            <img src="{{ asset($media->path) }}" width="50" height="50"
                                                alt="">
                                        </div>
                                    </td>
                                    <td><strong>{{ $media->dimension }}</strong></td>
                                    <td>
                                        <p>{{ asset($media->path) }}</p>
                                    </td>
                                    <td>
                                        @can('delete_media')
                                            <form action="{{ route('media.destroy', ['medium' => $media]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">TRASH</button>
                                            </form>
                                        @endcan
                                        <a href="#" data-url="{{ asset($media->path) }}"
                                            class="btn btn-success copy_url">COPY
                                            URL</a>
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
            $("#media_table").dataTable();
            $(document).on('click', '.copy_url', function(e) {
                e.preventDefault();
                var url = $(this).data("url");
                navigator.clipboard.writeText(url);
            });

        });
    </script>
@endsection
