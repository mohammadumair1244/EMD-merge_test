@extends('admin')
@section('head')
    <style>
        .tox .tox-notification--warn,
        .tox .tox-notification--warning {
            display: none !important;
        }

        .row {
            position: relative;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                <h4 class="my-3">Laravel Log File:</h4>
                <div class="row">
                    <div class="col-md-4">
                        @if ($available)
                            @can('view_log_file')
                                <a href="{{ route('emd_laravel_log_read') }}" class="btn btn-success">View</a>
                            @endcan
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if ($available)
                            <form action="{{ route('emd_laravel_log_delete') }}" method="post">
                                @csrf
                                @method('DELETE')
                                @can('delete_log_file')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                @endcan
                            </form>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if ($available)
                            <form action="{{ route('emd_laravel_log_download') }}" method="post">
                                @csrf
                                @can('download_log_file')
                                    <button type="submit" class="btn btn-primary">Download</button>
                                @endcan
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
