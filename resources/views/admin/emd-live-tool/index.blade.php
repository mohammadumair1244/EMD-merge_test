@extends('admin')
@section('head')
    <style>
        .emd_display_none {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">EMD Live Website Tools</h4>

                    <table id="get_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Lang</th>
                                <th>Slug</th>
                                <th>Get From Live</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (@$tools as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item['name'] }}</td>
                                    <td>{{ @$item['lang'] }}</td>
                                    <td>{{ @$item['slug'] }}</td>
                                    <th>
                                        @can('get_live_tool')
                                            <a data-lang="{{ @$item['lang'] }}" data-slug="{{ @$item['slug'] }}"
                                                data-loop="{{ @$loop->iteration }}" class="btn btn-info get_tool_content">Get
                                                From
                                                Live</a><span data-loop2="{{ @$loop->iteration }}"
                                                class="emd_display_none">Wait</span>
                                        </th>
                                    @endcan
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
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            $("#get_table").dataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("click", ".get_tool_content", function() {
                $(this).removeClass("get_tool_content");
                var d_lang = $(this).attr("data-lang");
                var d_slug = $(this).attr("data-slug");
                var d_loop = $(this).attr("data-loop");
                $('[data-loop2="' + d_loop + '"]').removeClass('emd_display_none');
                $.ajax({
                    url: "{{ route('emd_get_single_tool_api') }}",
                    method: 'POST',
                    data: {
                        d_lang: d_lang,
                        d_slug: d_slug,
                        d_loop: d_loop,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(ret) {
                        $('[data-loop="' + ret.d_loop + '"]').addClass('get_tool_content');
                        $('[data-loop2="' + d_loop + '"]').addClass('emd_display_none');
                    }
                });
            });
        });
    </script>
@endsection
