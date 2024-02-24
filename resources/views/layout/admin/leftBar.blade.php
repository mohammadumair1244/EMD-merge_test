<div class="left-side-menu" style="margin-top: -70px;">
    <a href="{{ route('home') }}" target="_blank" class="logo-box" style="padding-left: 15px;">
        <div class="logo">
            <img src="{{ asset('web_assets/admin/images/users/user-1.jpg') }}" alt="">
        </div>
        <div class="text">
            <span class="name">{{ config('app.name') }}</span>
            <span class="url">{{ route('home') }}</span>
        </div>
    </a>
    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
        <li>
            <button class="button-menu-mobile waves-effect waves-light">
                <i class="fe-menu"></i>
            </button>
        </li>

        <li>
            <!-- Mobile menu toggle (Horizontal Layout)-->
            <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
            <!-- End mobile menu toggle-->
        </li>
    </ul>
    <div class="h-100" style="padding-bottom: 70px;" data-simplebar>
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="active">
                        <img src="{{ asset('web_assets/admin/images/dashboard.png') }}" alt="">
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="#users" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/admin.png') }}" alt="">
                        <span> Admins </span>
                        <span class="menu-arrow"> </span>
                    </a>
                    <div class="collapse" id="users" style="">
                        <ul class="nav-second-level">
                            @can('view_team_member')
                                <li>
                                    <a href="{{ route('user.index') }}">
                                        List
                                    </a>
                                </li>
                            @endcan
                            @can('team_member_add')
                                <li>
                                    <a href="{{ route('user.create') }}">
                                        Add
                                    </a>
                                </li>
                            @endcan
                            @can('view_trash_team_member')
                                <li>
                                    <a href="{{ route('admin.trash_list') }}">
                                        Trash
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#blog" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/blogger.png') }}" alt="">
                        <span> Blog </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="blog" style="">
                        <ul class="nav-second-level">
                            @can('view_blogs')
                                <li>
                                    <a href="{{ route('blog.index') }}">List</a>
                                </li>
                            @endcan
                            @can('add_blog')
                                <li>
                                    <a href="{{ route('blog.create') }}">Add</a>
                                </li>
                            @endcan
                            @can('view_trash_blog')
                                <li>
                                    <a href="{{ route('blog.trash_list') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#tools" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/coding.png') }}" alt="">
                        <span> Tools </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="tools">
                        <ul class="nav-second-level">
                            @can('view_tool')
                                <li>
                                    <a href="{{ route('tool.parent_tools') }}">Parent List</a>
                                </li>
                            @endcan
                            @can('view_tool')
                                <li>
                                    <a href="{{ route('tool.index') }}">List</a>
                                </li>
                            @endcan
                            @can('add_tool')
                                <li>
                                    <a href="{{ route('tool.create') }}">Add</a>
                                </li>
                            @endcan
                            @can('view_trash_tool')
                                <li>
                                    <a href="{{ route('tool.trash_list') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    </a>
                </li>
                @include('layout.admin.custom.leftBar_1')
                <li>
                    <a href="#custom_page" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Custom Page</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="custom_page">
                        <ul class="nav-second-level">
                            @can('custom_page_view')
                                <li>
                                    <a href="{{ route('custom_page.view_page') }}">List</a>
                                </li>
                            @endcan
                            @can('custom_page_add')
                                <li>
                                    <a href="{{ route('custom_page.add_page') }}">Add</a>
                                </li>
                            @endcan
                            @can('custom_page_trash_view')
                                <li>
                                    <a href="{{ route('custom_page.trash_page') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd-pricing-plan" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/coding.png') }}" alt="">
                        <span>Price Plan </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd-pricing-plan">
                        <ul class="nav-second-level">
                            @can('view_pricing_plan')
                                <li>
                                    <a href="{{ route('emd_pricing_plan_view') }}">List</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_pricing_plan_view', ['type' => 1]) }}">Mobile</a>
                                </li>
                            @elsecan('view_dynamic_pricing_plan')
                                <li>
                                    <a href="{{ route('emd_pricing_plan_view') }}">List Custom</a>
                                </li>
                            @elsecan('view_custom_pricing_plan')
                                <li>
                                    <a href="{{ route('emd_pricing_plan_view') }}">List Dynamic</a>
                                </li>
                            @endcan
                            @can('add_pricing_plan')
                                <li>
                                    <a href="{{ route('emd_pricing_plan_add') }}">Add</a>
                                </li>
                            @endcan
                            @can('view_trash_pricing_plan')
                                <li>
                                    <a href="{{ route('emd_pricing_plan_trash') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    </a>
                </li>
                <li>
                    <a href="#settings" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/settings.png') }}" alt="">
                        <span> Settings </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="settings">
                        <ul class="nav-second-level">
                            @can('view_setting')
                                <li>
                                    <a href="{{ route('dashboard.settings') }}">Key Setting</a>
                                </li>
                            @endcan
                            @can('view_chat')
                                <li>
                                    <a href="{{ route('emd_chat_page') }}">Chat Setting</a>
                                </li>
                            @endcan
                            @if (config('emd_setting_keys.emd_tool_api_route_for_live') != '' &&
                                    config('emd_setting_keys.emd_tool_api_key_for_live') != '')
                                @can('get_live_tool')
                                    <li>
                                        <a href="{{ route('emd_tool_get_page') }}">Live Web Tools</a>
                                    </li>
                                @endcan
                            @endif
                            @can('view_all_permission_list')
                                <li>
                                    <a href="{{ route('view_all_permission_page') }}">Permissions</a>
                                </li>
                            @endcan
                            @can('view_microsoft_clarity')
                                <li>
                                    <a href="{{ route('clarity.view_page') }}">Clarity Setting</a>
                                </li>
                            @endcan
                            @can('view_log_file')
                                <li>
                                    <a href="{{ route('emd_laravel_log_page') }}">
                                        Log Files
                                    </a>
                                </li>
                            @endcan
                            @can('view_migrate_status')
                                <li>
                                    <a href="{{ route('emd_view_migrate_status_page') }}">
                                        Migrate Status
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd_web_user" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Web User</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_web_user">
                        <ul class="nav-second-level">
                            @can('view_users')
                                <li>
                                    <a href="{{ route('emd_view_web_users') }}">List</a>
                                </li>
                            @endcan
                            @can('view_random_users')
                                <li>
                                    <a href="{{ route('emd_view_random_web_users') }}">Random</a>
                                </li>
                            @endcan
                            @can('view_trash_user')
                                <li>
                                    <a href="{{ route('emd_view_web_users_trash') }}">Trash</a>
                                </li>
                            @endcan
                            @can('export_web_users')
                                <li>
                                    <a href="{{ route('web_users_export_page') }}">Export</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd_custom_fields" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Custom Fields </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_custom_fields">
                        <ul class="nav-second-level">
                            @can('view_custom_field')
                                <li>
                                    <a href="{{ route('custom_field.view_page') }}">List</a>
                                </li>
                            @endcan
                            @can('add_custom_field')
                                <li>
                                    <a href="{{ route('custom_field.add_page') }}">Add</a>
                                </li>
                            @endcan
                            @can('trash_view_custom_field')
                                <li>
                                    <a href="{{ route('custom_field.trash_view_page') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd_transaction" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span> Transaction</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_transaction">
                        <ul class="nav-second-level">
                            @can('view_all_transactions')
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_PROCESSED]) }}">Processed</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_CHARGE_BACK]) }}">Chargeback</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_REFUNDED]) }}">Refunded</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_CANCELED]) }}">Canceled</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_WAITING]) }}">Waiting</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_all_transaction', ['type' => App\Models\EmdUserTransaction::OS_CHANGE_PLAN]) }}">Change
                                        Plan</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_transaction_search_page') }}">Search Order</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_transaction_without_original', ['type' => App\Models\EmdUserTransaction::TEST_MODE]) }}">Test</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('emd_transaction_without_original', ['type' => App\Models\EmdUserTransaction::REGISTER_MODE]) }}">Register</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_transaction_logs_page') }}">Trans Logs</a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#media" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/gallery.png') }}" alt="">
                        <span> Media </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="media">
                        <ul class="nav-second-level">
                            @can('view_media')
                                <li>
                                    <a href="{{ route('media.create') }}">List / Add</a>
                                </li>
                            @endcan
                            @can('view_trash_media')
                                <li>
                                    <a href="{{ route('media.trash_list') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    </a>
                </li>
                @include('layout.admin.custom.leftBar_2')
                <li>
                    <a href="#contacts" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span> Contacts </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="contacts">
                        <ul class="nav-second-level">
                            @can('view_contact_us')
                                <li>
                                    <a href="{{ route('contact.index') }}">List</a>
                                </li>
                            @endcan
                            @can('view_trash_contact_us')
                                <li>
                                    <a href="{{ route('dashboard.contacts.trash') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>

                    </div>
                </li>

                <li>
                    <a href="#emd_email_setting" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Email Setting</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_email_setting">
                        <ul class="nav-second-level">
                            @can('view_email_setting')
                                <li>
                                    <a href="{{ route('emd_email_setting_type_page', ['type' => 1]) }}">Contact Us</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_email_setting_type_page', ['type' => 2]) }}">Forgot
                                        Password</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_email_setting_type_page', ['type' => 3]) }}">New Account</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_email_setting_type_page', ['type' => 4]) }}">Account Delete</a>
                                </li>
                                <li>
                                    <a href="{{ route('emd_email_setting_type_page', ['type' => 5]) }}">Cancel
                                        Membership</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd_component" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Component</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_component">
                        <ul class="nav-second-level">
                            @can('view_component')
                                <li>
                                    <a href="{{ route('component.view_page') }}">List</a>
                                </li>
                            @endcan
                            @can('add_component')
                                <li>
                                    <a href="{{ route('component.add_page') }}">Add</a>
                                </li>
                            @endcan
                            @can('trash_view_component')
                                <li>
                                    <a href="{{ route('component.trash_view_page') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#emd_feedback" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Feedback </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="emd_feedback">
                        <ul class="nav-second-level">
                            @can('view_feedback_list')
                                <li>
                                    <a href="{{ route('emd_feedback_page') }}">List</a>
                                </li>
                            @endcan
                            @can('view_trash_feedback_list')
                                <li>
                                    <a href="{{ route('emd_trash_feedback_page') }}">Trash</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#email_campaign" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Email Campaign</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="email_campaign">
                        <ul class="nav-second-level">
                            @can('email_campaign_email_list')
                                <li>
                                    <a href="{{ route('campaign.emd_email_list_page') }}">Email
                                        List</a>
                                </li>
                            @endcan
                            @can('email_campaign_user_list')
                                <li>
                                    <a href="{{ route('campaign.view_users_page') }}">User
                                        List</a>
                                </li>
                            @endcan
                            @can('email_campaign_manage')
                                <li>
                                    <a href="{{ route('campaign.add_page') }}">Create Campaign</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#email_template" data-bs-toggle="collapse" class="collapsed" aria-expanded="false">
                        <img src="{{ asset('web_assets/admin/images/letter.png') }}" alt="">
                        <span>Email Template</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="email_template">
                        <ul class="nav-second-level">
                            @can('email_template_view')
                                <li>
                                    <a href="{{ route('template.view_page') }}">List</a>
                                </li>
                            @endcan
                            @can('email_template_add')
                                <li>
                                    <a href="{{ route('template.add_page') }}">Add</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @can('view_modals')
                    <li>
                        <a href="{{ route('dashboard.components') }}" class="active">
                            <img src="{{ asset('web_assets/admin/images/tech.png') }}" alt="">
                            <span> Modals </span>
                        </a>
                    </li>
                @endcan
                @include('layout.admin.custom.leftBar_3')
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
