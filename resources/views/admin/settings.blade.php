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
                <h4 class="my-3">Media:</h4>
                <form id="" method="POST" action="{{ route('dashboard.update.settings') }}">
                    @csrf
                    {{-- <div class="row"> --}}
                    <div class="row">
                        @foreach ($media as $item)
                            <div class="col-md-3 mb-3">
                                <label for=""
                                    class="form-label">{{ Str::ucfirst(str_replace('_', ' ', $item->key)) }}</label>
                                <input type="text" name="{{ $item->key }}" class="form-control"
                                    value="{{ $item->value }}"
                                    placeholder="{{ Str::ucfirst(str_replace('_', ' ', $item->key)) }}" readonly=""
                                    required>
                            </div>
                        @endforeach
                    </div>
                    <h4 class="my-3">Key Setting:</h4>
                    <div class="tool_content">
                        @foreach ($content as $item)
                            <div class="row" id="{{ $item->key }}-row">
                                <input type="hidden" value="{{ $item->type }}" name="inputType[]">
                                <input type="hidden" value="{{ $item->section }}" value="" name="sectionType[]">
                                <div class="col-md-3 mb-3">
                                    <input type="text" name="contentKey[]" class="form-control" placeholder="Key"
                                        value="{{ $item->key }}" required>
                                </div>
                                <div class="col-md-9 mb-3">
                                    @if ($item->type == 'inputField')
                                        <input type="text" name="contentValue[]" class="form-control" placeholder="Value"
                                            value="{{ $item->value }}">
                                    @elseif ($item->type == 'textarea')
                                        <textarea rows="3" name="contentValue[]" class="form-control" placeholder="Content">{{ $item->value }}</textarea>
                                    @else
                                        <input type="text" class="form-control tool_textarea"
                                            value="{{ $item->value }}" name="contentValue[]" />
                                    @endif
                                    <span style="margin-left: 15px;">{{ config('emd_setting_keys.' . $item->key) }}</span>
                                </div>
                                @can('delete_setting')
                                    <a class="cross" data-id="{{ $item->id }}">&times;</a>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <select class="form-select" id="add_more_type" name="add_more_type">
                                <option selected disabled>Select Input Type</option>
                                <option value="inputField">Input Fields</option>
                                <option value="textarea">Text Area</option>
                                <option value="richText">Rich Text Editor</option>
                            </select>

                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="#" class="btn btn-primary waves-effect waves-light" id="addMore">Add
                                Row</a>
                        </div>
                    </div>
                    {{-- </div> --}}
                    <div style="text-align: right">
                        @can('add_update_setting')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        @endcan
                    </div>
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
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('label').on('click', function() {
                $(this).next().focus();
            });
            $(document).on('click', 'a.cross', function() {
                var id = $(this).data('id');
                let _this = $(this);
                if (id != "") {
                    if (confirm('Are You Sure?')) {
                        $.ajax({
                            type: "GET",
                            data: {
                                id,
                            },
                            url: "{{ route('setting.delete') }}",
                            success: function(response) {
                                if (parseInt(response) == 1) {
                                    _this.parent('.row').remove();
                                }
                            }
                        });
                    }
                } else {
                    $(this).parent('.row').remove();
                }
            });
            $(document).on('click', 'span.cross', function() {
                $(this).parent('.row').remove();
            });
        });
        jQuery("#addMore").on("click", function(e) {
            e.preventDefault();
            var selectedValue = $("#add_more_type").val();
            var html = `<div class="row">
                <span class="cross">&times;</span>
                <input type="hidden" value="` + selectedValue + `" name="inputType[]">
                <input type="hidden" value="content" value="" name="sectionType[]">
                    <div class="col-md-3 mb-3">
                        <input type="text" name="contentKey[]" class="form-control" placeholder="Key" value="" required>
                    </div>
                    <div class="col-md-8 mb-3">`;
            if (selectedValue == "inputField") {
                html +=
                    `<input type="text" name="contentValue[]" class="form-control" placeholder="Value" value="" required>`;
            } else if (selectedValue == "textarea") {
                html +=
                    `<textarea rows="3" name="contentValue[]" class="form-control" placeholder="Content" value="" ></textarea>`;
            } else {
                html += `<input class="form-control tool_textarea" name="contentValue[]"   />`;
            }
            $(".tool_content").append(html);
            init_tinymce();
        });
    </script>
@endsection
