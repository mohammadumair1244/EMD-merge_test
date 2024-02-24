@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .feature {
            max-width: 250px;
            width: 60px;
            border-radius: 50%;
        }

        tbody tr td {
            vertical-align: middle;
        }

        .status_label {
            color: white;
            padding: 1px 15px;
            border-radius: 5px;
        }

        .premium {
            background: green;
        }

        .free {
            background: red;
        }

        .refunded {
            background: black;
        }

        .expired {
            background: orange;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Create Campaign</h4>
                    <table id="users_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>User Status</th>
                                <th>No of Users</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($user_status as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item->count() }}</td>
                                </tr>
                            @endforeach
                            @foreach ($emails_list as $index => $email_list)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $index }}</td>
                                    <td>{{ $email_list->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3">
                            @can('email_campaign_add')
                                <h4>Create New Campaign</h4>
                                <form action="{{ route('campaign.create_page') }}" method="post">
                                    @csrf
                                    <label for="">From EMail</label>
                                    <input type="email" name="from_email" class="form-control" required="" id="">
                                    <label for="">From Name</label>
                                    <input type="text" name="from_name" class="form-control" required="">
                                    <label for="">From Subject</label>
                                    <input type="text" name="from_subject" class="form-control" required="">
                                    <label for="">Campaign Title</label>
                                    <input type="text" class="form-control" required="" name="title">
                                    <label for="">Start Date</label>
                                    <input type="date" min="{{ date('Y-m-d') }}" class="form-control"
                                        value="{{ date('Y-m-d') }}" name="start_date">
                                    <label for="">Select User Status</label>
                                    <select multiple name="user_status[]" class="form-control">
                                        @foreach ($user_status as $key => $value)
                                            <option value="{{ $key }}">{{ $key . ' (' . $value->count() . ')' }}
                                            </option>
                                        @endforeach
                                        @foreach ($emails_list as $index_key => $email_val)
                                            <option value="{{ $index_key }}">
                                                {{ $index_key . ' (' . $email_val->count() . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="">No of Mails (per hour)</label>
                                    <input type="number" min="1" max="2000" name="per_hour_emails"
                                        class="form-control">
                                    <label for="">Email Template</label>
                                    <select name="emd_email_template_id" class="form-control" id="">
                                        @foreach ($email_templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="">Testing Email</label>
                                    <input type="email" name="testing_email" class="form-control" required="">
                                    <br>
                                    <button type="submit" class="btn btn-info">Save</button>
                                </form>
                            @endcan
                        </div>
                        <div class="col-md-9">
                            @can('email_campaign_view')
                                <h4>Campaign List</h4>
                                <table class="table">
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Template / Campaign</th>
                                        <th>From Email / From Name / From Subject</th>
                                        <th>Start Date</th>
                                        <th>User Status</th>
                                        <th>Per Hour Emails</th>
                                        <th>Sended / Total Emails</th>
                                        <th>Testing Email</th>
                                        <th>Status</th>
                                    </tr>
                                    @foreach ($campaign_lists as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->emd_email_template->title }} <br>{{ $item->title }}</td>
                                            <td>{{ $item->from_email }} <br> {{ $item->from_name }} <br>
                                                {{ $item->from_subject }} </td>
                                            <td>{{ $item->start_date }}</td>
                                            <td>{{ $item->user_status }}</td>
                                            <td>{{ $item->per_hour_emails }}</td>
                                            <td>{{ $item->send_emails . ' / ' . $item->total_emails }}</td>
                                            <td>
                                                {{ $item->testing_email }} <br>
                                                @can('email_campaign_change_status')
                                                    <form action="{{ route('campaign.send_test_email') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $item->testing_email }}"
                                                            name="testing_email">
                                                        <input type="hidden" value="{{ $item->id }}" name="id">
                                                        <button class="btn btn-primary" type="submit">Send Testing</button>
                                                    </form>
                                                @endcan
                                            </td>
                                            <td>
                                                @can('email_campaign_change_status')
                                                    @if ($item->status == 0)
                                                        <a href="{{ route('campaign.change_status', ['id' => $item->id, 'status' => 1]) }}"
                                                            class="btn btn-info">Start</a>
                                                    @elseif ($item->status == 1)
                                                        <a href="{{ route('campaign.change_status', ['id' => $item->id, 'status' => 0]) }}"
                                                            class="btn btn-warning">Stop</a>
                                                    @else
                                                        <b>Completed</b>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endcan
                        </div>
                    </div>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
