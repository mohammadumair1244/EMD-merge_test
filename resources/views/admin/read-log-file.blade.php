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
                <h4 class="my-3">{{ request()->route('file_name') }} Read:</h4>
                <div class="row">
                    <div class="col-md-12">
                        <pre>{{ $log_content }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
