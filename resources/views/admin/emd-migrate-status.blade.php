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

        .line-wise {
            padding: 10px;
            background: #80808024;
            border-radius: 5px;
            margin-top: 3px !important;
        }

        .line-wise-heading {
            font-weight: 700;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="card">
            <div class="card-body pt-0">
                <div class="row line-wise line-wise-heading">
                    <div class="col-md-1">
                        #
                    </div>
                    <div class="col-md-9">
                        Migration name
                    </div>
                    <div class="col-md-2">
                        Batch / Status
                    </div>
                </div>
                @php
                    $i = 1;
                @endphp
                @foreach ($migrate_status as $line)
                    @if (trim($line) != '' && $loop->iteration > 2)
                        @php
                            $line = str_replace('.', '', $line);
                            $posOpeningBracket = strpos($line, '[');
                        @endphp
                        <div class="row line-wise">
                            <div class="col-md-1">
                                ({{ $i++ }})
                            </div>
                            <div class="col-md-9">
                                {{ trim(substr($line, 0, $posOpeningBracket)) }}
                            </div>
                            <div class="col-md-2">
                                {{ trim(substr($line, $posOpeningBracket)) }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
