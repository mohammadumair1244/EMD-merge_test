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
                <h4 class="my-3">Set Chat Status:</h4>
                <form id="" method="POST" action="{{ route('emd_chat_req') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="" class="form-label">Change Chat Status</label>
                            <select name="chat_status" class="form-control" id="">
                                <option value="0">Off</option>
                                <option value="1" {{ $chat_status == '1' ? 'selected' : '' }}>On</option>

                            </select>
                            <br>
                            @can('on_off_chat')
                                <button type="submit" class="btn btn-info">Save</button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
@endsection
