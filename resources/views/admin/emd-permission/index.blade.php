@extends('admin')
@section('head')
    <style>
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
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Permissions</h4>
                    <table id="users_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Type</th>
                                {{-- <th>Key</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item->name }}</td>
                                    <td><span class="permission permission_type{{ @$item->type ?: 0 }}">{{ \App\Models\EmdPermission::TYPE[@$item->type] }}</span> </td>
                                    {{-- <td>{{ @$item->key }}</td> --}}
                                </tr>
                            @empty
                            @endforelse

                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
