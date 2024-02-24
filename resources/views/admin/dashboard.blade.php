@extends('admin')
@section('head')
    <style type="text/css">
        .stat-block {
            background: #80808042;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            box-shadow: 2px 2px 3px #8080807a;
            margin-top: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    @if (config('emd_setting_keys.emd_dashboard_stats') == 1)
                        @can('dashboard_stats')
                            <div class="row">
                                @can('view_team_member')
                                    <div class="col-md-2">
                                        <a href="{{ route('user.index') }}">
                                            <div class="stat-block">
                                                <h4>Admins</h4>
                                                <b>{{ @$dashboard_stats['total_admins_count'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_users_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_users')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_view_web_users') }}">
                                            <div class="stat-block">
                                                <h4>Register User</h4>
                                                <b>{{ @$dashboard_stats['total_web_users_count'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_users_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_users')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_view_web_users') }}">
                                            <div class="stat-block">
                                                <h4>Null User</h4>
                                                <b>{{ @$dashboard_stats['total_web_null_users_count'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_users_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_users')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_view_web_users_type_wise', ['type' => 1]) }}">
                                            <div class="stat-block">
                                                <h4>Web Premium</h4>
                                                <b>{{ @$dashboard_stats['total_web_premium'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_web_premium'] + @$dashboard_stats['total_api_premium'] + @$dashboard_stats['total_web_api_premium'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_users')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_view_web_users_type_wise', ['type' => 1]) }}">
                                            <div class="stat-block">
                                                <h4>API Premium</h4>
                                                <b>{{ @$dashboard_stats['total_api_premium'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_web_premium'] + @$dashboard_stats['total_api_premium'] + @$dashboard_stats['total_web_api_premium'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_users')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_view_web_users_type_wise', ['type' => 1]) }}">
                                            <div class="stat-block">
                                                <h4>Web & API Premium</h4>
                                                <b>{{ @$dashboard_stats['total_web_api_premium'] ?: 0 }} /
                                                    {{ @$dashboard_stats['total_web_premium'] + @$dashboard_stats['total_api_premium'] + @$dashboard_stats['total_web_api_premium'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_feedback_list')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_feedback_page') }}">
                                            <div class="stat-block">
                                                <h4>Feedbacks</h4>
                                                <b>{{ @$dashboard_stats['total_feedbacks_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_contact_us')
                                    <div class="col-md-2">
                                        <a href="{{ route('contact.index') }}">
                                            <div class="stat-block">
                                                <h4>Contacts</h4>
                                                <b>{{ @$dashboard_stats['total_contacts_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_all_transactions')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_all_transaction', ['type' => 'Processed']) }}">
                                            <div class="stat-block">
                                                <h4>Processed Trans</h4>
                                                <b>{{ @$dashboard_stats['total_PTrans_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_all_transactions')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_all_transaction', ['type' => 'Canceled']) }}">
                                            <div class="stat-block">
                                                <h4>Cancel Trans</h4>
                                                <b>{{ @$dashboard_stats['total_CTrans_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                                @can('view_all_transactions')
                                    <div class="col-md-2">
                                        <a href="{{ route('emd_all_transaction', ['type' => 'Refunded']) }}">
                                            <div class="stat-block">
                                                <h4>Refund Trans</h4>
                                                <b>{{ @$dashboard_stats['total_RTrans_count'] ?: 0 }}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                            <br>
                        @endcan
                    @endif
                    <h4 class="header-title">Tools Audit</h4>
                    <table id="" class="table">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Tool</th>
                                <th>Audit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tools_with_audits as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <a href="{{ route('tool.audit', ['id' => $item->id]) }}">Audit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
