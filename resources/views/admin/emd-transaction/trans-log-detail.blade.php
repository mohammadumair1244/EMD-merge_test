@extends('admin')
@section('head')
    <style>
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Transaction Log JSON</h4>

                    <table class="table dt-responsive nowrap w-100  order-column">
                        <tbody>
                            @if (@$emd_trans_log->trans_log)
                                @if (gettype(json_decode(@$emd_trans_log->trans_log, true)) == 'array')
                                    @foreach (json_decode(@$emd_trans_log->trans_log, true) as $key => $value)
                                        <tr>
                                            <td>
                                                {{ Str::replace('_', ' ', $key) }}
                                            </td>
                                            <td>
                                                {{ $key == 'CHECKOUT_QUERY_STRING' ? htmlspecialchars_decode(urldecode($value)) : $value }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
