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
                @forelse($log_files as $log_file)
                    <h4 class="my-3">{{ $log_file }} File:</h4>
                    <div class="row">
                        <div class="col-md-4">
                            @can('view_log_file')
                                <a href="{{ route('emd_laravel_log_read', ['file_name' => $log_file]) }}"
                                    class="btn btn-success">View</a>
                            @endcan
                        </div>
                        <div class="col-md-4">
                            <form action="{{ route('emd_laravel_log_delete') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" value="{{ $log_file }}" name="file_name">
                                @can('delete_log_file')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                @endcan
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ route('emd_laravel_log_download', ['file_name' => $log_file]) }}"
                                method="post">
                                @csrf
                                <input type="hidden" value="{{ $log_file }}" name="file_name">
                                @can('download_log_file')
                                    <button type="submit" class="btn btn-primary">Download</button>
                                @endcan
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="row">
                        <h4 class="text-center">Log File Not Available</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
