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
                <h4 class="my-3">Email Setting</h4>
                <form id="" method="POST"
                    action="{{ route('emd_email_setting_req', ['type' => $email_setting->email_type]) }}">
                    @csrf
                    <h4 class="my-3">{{ $email_setting::TYPE_OF_EMAIL[$email_setting->email_type] }}:</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Is Active</label>
                            <select name="is_active" class="form-control" id="">
                                <option value="0">No</option>
                                <option value="1" {{ $email_setting->is_active == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Sender Email</label>
                            <input type="text" class="form-control" value="{{ $email_setting->sender_email }}"
                                name="sender_email" {{ $email_setting->sender_email == 'none' ? 'readonly' : '' }}
                                @required(true)>
                        </div>
                        <div class="col-md-6">
                            <label for="">Receiver Email</label>
                            <input type="text" class="form-control" value="{{ $email_setting->receiver_email }}"
                                name="receiver_email" {{ $email_setting->receiver_email == 'none' ? 'readonly' : '' }}
                                @required(true)>
                        </div>
                        <div class="col-md-6">
                            <label for="">Send From</label>
                            <input type="text" class="form-control" value="{{ $email_setting->send_from }}"
                                name="send_from" @required(true)>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email Title</label>
                            <input type="text" class="form-control" value="{{ $email_setting->subject }}" name="subject"
                                @required(true)>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email Template</label>
                            <input type="text" class="form-control tool_textarea" value="{{ $email_setting->template }}"
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
            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
    {{-- TINYMCE SCRIPT --}}
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script> --}}
    {{-- TINYMCE SCRIPT END --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"
        integrity="sha512-6JR4bbn8rCKvrkdoTJd/VFyXAN4CE9XMtgykPWgKiHjou56YDJxWsi90hAeMTYxNwUnKSQu9JPc3SQUg+aGCHw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('web_assets/admin/js/tinymce-script-2.js?v1.0.3') }}"></script>
@endsection
