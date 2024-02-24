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

        .cross {
            position: absolute;
            border-radius: 50%;
            background-color: red;
            width: 20px !important;
            height: 24px;
            right: -20px;
            top: 6px;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 10;
            font-size: 16px;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                <h4 class="my-3">Email Setting:</h4>
                @foreach ($email_settings as $item)
                    <form id="" method="POST"
                        action="{{ route('emd_email_setting_req', ['type' => $item->email_type]) }}">
                        @csrf
                        <h4 class="my-3">{{ $item::TYPE_OF_EMAIL[$item->email_type] }}:</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Is Active</label>
                                <select name="is_active" class="form-control" id="">
                                    <option value="0">No</option>
                                    <option value="1" {{ $item->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Sender Email</label>
                                <input type="text" class="form-control" value="{{ $item->sender_email }}"
                                    name="sender_email" {{ $item->sender_email == 'none' ? 'readonly' : '' }}
                                    @required(true)>
                            </div>
                            <div class="col-md-6">
                                <label for="">Receiver Email</label>
                                <input type="text" class="form-control" value="{{ $item->receiver_email }}"
                                    name="receiver_email" {{ $item->receiver_email == 'none' ? 'readonly' : '' }}
                                    @required(true)>
                            </div>
                            <div class="col-md-6">
                                <label for="">Send From</label>
                                <input type="text" class="form-control" value="{{ $item->send_from }}" name="send_from"
                                    @required(true)>
                            </div>
                            <div class="col-md-6">
                                <label for="">Email Title</label>
                                <input type="text" class="form-control" value="{{ $item->subject }}" name="subject"
                                    @required(true)>
                            </div>
                            <div class="col-md-6">
                                <label for="">Email Template</label>
                                <input type="text" class="form-control tool_textarea" value="{{ $item->template }}"
                                    name="template" @required(true) />
                            </div>
                        </div>
                        <div style="text-align: right">
                            @can('add_update_email_setting')
                            <br>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Submit
                                </button>
                            @endcan
                        </div>
                        <hr>
                    </form>
                @endforeach
            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
    {{-- TINYMCE SCRIPT --}}
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script defer src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin">
    </script>
    {{-- TINYMCE SCRIPT END --}}
    <script src="{{ asset('web_assets/admin/js/tinymce-script.js?v1.0.2') }}"></script>
@endsection
