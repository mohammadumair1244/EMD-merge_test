@extends('admin')
@section('head')
    <style>
        .tox .tox-notification--warn,
        .tox .tox-notification--warning {
            display: none !important;
        }

        .images_row {
            background: transparent;
            row-gap: 0.5em;
        }

        .images_row div.image-box {
            background: gainsboro;
        }

        .img-fluid.rounded {
            height: 150px;
            object-fit: contain;
            width: 100%;
            background: #8080802e;
            padding: 5px;
        }

        .feature_img_preview img {
            max-width: 300px;
            width: 100%;
            object-fit: contain;
        }

        .permission-name {
            font-size: 14px;
            color: #343a40;
        }

        hr {
            margin: 5px !important;
        }

        .permission {
            /* font-style: italic; */
            color: white;
            padding: 1px 10px;
            border-radius: 13px;
            font-size: 12px;
        }

        .permission_type0 {
            background: black;
        }

        .permission_type1 {
            background: #ff0000b5;
        }

        .permission_type2 {
            background: #00800091;
        }

        .permission_type3 {
            background: #f9a02fed;
        }

        .permission_type4 {
            background: #009fffb5;
            ;
        }

        .permission_type5 {
            background: brown;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                <div class="message">
                </div>
                <h4 class="my-3">Update User:</h4>
                <form id="update_admin_form" method="POST" action="{{ route('user.update', ['user' => $user]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email"
                                value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password"
                                value="" required>
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('team_member_edit')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Update
                            </button>
                        @endcan
                    </div>
                </form>
                <div class="row">
                    @foreach (\App\Models\EmdPermission::TYPE as $key => $value)
                        @if ($key > 0)
                            <div class="col-md-3">
                                <input type="checkbox" class="set_permission" id="{{ $key }}">
                                <span>{{ $value }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                @can('allow_permission')
                    <h4>Allow Permission</h4>
                    <form action="{{ route('allow_team_permision_req', ['user_id' => request()->route('user')->id]) }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            @foreach (@$emd_permissions as $item)
                                @if ($currentUser->admin_level == \App\Models\User::ADMIN || $currentUser->emd_permission->contains($item->id))
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <b class="permission-name">{{ $item->name }}</b>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="checkbox" class="permission_type{{ $item->type ?: 0 }}"
                                                    value="{{ $item->id }}" name="emd_permissions[]"
                                                    @checked($user->emd_permission->contains($item->id))>
                                            </div>
                                        </div>
                                        <span
                                            class="permission permission_type{{ @$item->type }}">{{ \App\Models\EmdPermission::TYPE[$item->type ?: 0] }}</span>
                                        <hr>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <br>
                        <button type="submit" class="btn btn-info">Save</button>
                    </form>
                @endcan

            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".set_permission").click(function() {
                var key = $(this).attr('id');
                var is_check = $(this).is(':checked');
                if (is_check) {
                    $(".permission_type" + key).prop("checked", true);
                } else {
                    $(".permission_type" + key).prop("checked", false);
                }
            });

        });
    </script>
@endsection
