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
                    <h4 class="header-title">Create Email Template</h4>
                    <div class="row">
                        <div class="col-md-6">
                            @can('email_template_add')
                                <form action="{{ route('template.create_page') }}" method="post">
                                    @csrf
                                    <label for="">Template Title</label>
                                    <input type="text" class="form-control" required="" name="title">
                                    <label for="">Body</label>
                                    <input type="text" name="body" class="form-control tool_textarea">
                                    <br>
                                    <button type="submit" class="btn btn-info">Save</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin">
    </script>
    {{-- TINYMCE SCRIPT END --}}
    <script src="{{ asset('web_assets/admin/js/tinymce-script.js?v1.0.2') }}"></script>
@endsection
