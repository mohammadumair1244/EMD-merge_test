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
    </style>
@endsection
@section('content')
    <div class="row mt-4">
        <div class="card">
            <div class="card-body pt-0">
                <h4 class="my-3">Add User:</h4>
                <form id="admin_form" method="POST" action="{{ route('user.store') }}">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password"
                                value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="" class="form-label">Role</label>
                                <select class="form-control" name="admin_level" value="{{ old('admin_level') }}" required>
                                    @foreach (\App\Models\User::USER_TYPE as $key => $item)
                                        @if ($key != \App\Models\User::ADMIN && $key != \App\Models\User::WEB_REGISTER)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('team_member_add')
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
        });
    </script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error("{{ $error }}");
            </script>
        @endforeach
    @endif
@endsection
