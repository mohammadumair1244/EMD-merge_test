<?php

namespace Database\Seeders;

use App\Models\EmdPermission;
use Illuminate\Database\Seeder;

class EmdPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            [
                'name' => 'Users List',
                'key' => 'view_users',
                'type' => 2,
            ],
            [
                'name' => 'User Detail',
                'key' => 'view_user_detail',
                'type' => 2,
            ],
            [
                'name' => 'Users Comments List',
                'key' => 'view_user_comment',
                'type' => 2,
            ],
            [
                'name' => 'Users Transactions List',
                'key' => 'view_user_transaction',
                'type' => 2,
            ],
            [
                'name' => 'User Search',
                'key' => 'user_search',
                'type' => 2,
            ],
            [
                'name' => 'User Delete',
                'key' => 'user_delete',
                'type' => 1,
            ],
            [
                'name' => 'User Restore',
                'key' => 'user_restore',
                'type' => 1,
            ],
            [
                'name' => 'User Update',
                'key' => 'user_update',
                'type' => 2,
            ],
            [
                'name' => 'User Set Custom Premium',
                'key' => 'user_set_custom_premium',
                'type' => 2,
            ],
            [
                'name' => 'User Add Web Query',
                'key' => 'user_add_web_query',
                'type' => 2,
            ],
            [
                'name' => 'User Add API Query',
                'key' => 'user_add_api_query',
                'type' => 2,
            ],
            [
                'name' => 'Users Trash List',
                'key' => 'view_trash_user',
                'type' => 2,
            ],
            [
                'name' => 'Team Members List',
                'key' => 'view_team_member',
                'type' => 2,
            ],
            [
                'name' => 'Team Member Add',
                'key' => 'team_member_add',
                'type' => 1,
            ],
            [
                'name' => 'Team Member Edit',
                'key' => 'team_member_edit',
                'type' => 1,
            ],
            [
                'name' => 'Team Member Detail',
                'key' => 'team_member_detail',
                'type' => 1,
            ],
            [
                'name' => 'Team Member Delete',
                'key' => 'team_member_delete',
                'type' => 1,
            ],
            [
                'name' => 'Team Member Restore',
                'key' => 'team_member_restore',
                'type' => 1,
            ],
            [
                'name' => 'Team Member Trash List',
                'key' => 'view_trash_team_member',
                'type' => 1,
            ],
            [
                'name' => 'Custom Pricing Plan Add',
                'key' => 'add_custom_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Blogs List',
                'key' => 'view_blogs',
                'type' => 3,
            ],
            [
                'name' => 'Blog Add',
                'key' => 'add_blog',
                'type' => 3,
            ],
            [
                'name' => 'Blog Trash List',
                'key' => 'view_trash_blog',
                'type' => 3,
            ],
            [
                'name' => 'Blog Edit',
                'key' => 'blog_edit',
                'type' => 3,
            ],
            [
                'name' => 'Blog Translation Btn',
                'key' => 'blog_translation_btn',
                'type' => 3,
            ],
            [
                'name' => 'Blog Delete',
                'key' => 'blog_delete',
                'type' => 1,
            ],
            [
                'name' => 'Blog Restore',
                'key' => 'blog_restore',
                'type' => 3,
            ],
            [
                'name' => 'Tools List',
                'key' => 'view_tool',
                'type' => 3,
            ],
            [
                'name' => 'Tool Add',
                'key' => 'add_tool',
                'type' => 3,
            ],
            [
                'name' => 'Tool Edit',
                'key' => 'edit_tool',
                'type' => 3,
            ],
            [
                'name' => 'Tool Restore',
                'key' => 'restore_tool',
                'type' => 3,
            ],
            [
                'name' => 'Tool Delete',
                'key' => 'delete_tool',
                'type' => 1,
            ],
            [
                'name' => 'Tool Translation Btn',
                'key' => 'tool_translation_btn',
                'type' => 3,
            ],
            [
                'name' => 'Tool Trash List',
                'key' => 'view_trash_tool',
                'type' => 3,
            ],
            [
                'name' => 'Pricing Plans List',
                'key' => 'view_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Pricing Plan Add',
                'key' => 'add_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Pricing Plan Trash List',
                'key' => 'view_trash_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Pricing Plan Edit',
                'key' => 'edit_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Pricing Plan Delete',
                'key' => 'delete_pricing_plan',
                'type' => 1,
            ],
            [
                'name' => 'Pricing Plan Restore',
                'key' => 'restore_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Media List',
                'key' => 'view_media',
                'type' => 3,
            ],
            [
                'name' => 'Media Add',
                'key' => 'add_media',
                'type' => 3,
            ],
            [
                'name' => 'Media Delete',
                'key' => 'delete_media',
                'type' => 1,
            ],
            [
                'name' => 'Media Restore',
                'key' => 'restore_media',
                'type' => 3,
            ],
            [
                'name' => 'Media Trash List',
                'key' => 'view_trash_media',
                'type' => 3,
            ],
            [
                'name' => 'Contact Us List',
                'key' => 'view_contact_us',
                'type' => 3,
            ],
            [
                'name' => 'Contact Us Delete',
                'key' => 'delete_contact_us',
                'type' => 1,
            ],
            [
                'name' => 'Contact Us Restore',
                'key' => 'restore_contact_us',
                'type' => 3,
            ],
            [
                'name' => 'Contact Us Trash List',
                'key' => 'view_trash_contact_us',
                'type' => 3,
            ],
            [
                'name' => 'Key Setting List',
                'key' => 'view_setting',
                'type' => 4,
            ],
            [
                'name' => 'Key Setting (Add & Update)',
                'key' => 'add_update_setting',
                'type' => 4,
            ],
            [
                'name' => 'Key Setting Delete',
                'key' => 'delete_setting',
                'type' => 1,
            ],
            [
                'name' => 'Email Settings List',
                'key' => 'view_email_setting',
                'type' => 3,
            ],
            [
                'name' => 'Email Setting (Add & Update)',
                'key' => 'add_update_email_setting',
                'type' => 3,
            ],
            [
                'name' => 'Transactions List',
                'key' => 'view_all_transactions',
                'type' => 2,
            ],
            [
                'name' => 'Transaction Detail',
                'key' => 'view_transaction_detail',
                'type' => 2,
            ],
            [
                'name' => 'Get Live Tool',
                'key' => 'get_live_tool',
                'type' => 4,
            ],
            [
                'name' => 'Permissions List',
                'key' => 'view_all_permission_list',
                'type' => 1,
            ],
            [
                'name' => 'Modals',
                'key' => 'view_modals',
                'type' => 3,
            ],
            [
                'name' => 'Permission Allows',
                'key' => 'allow_permission',
                'type' => 1,
            ],
            [
                'name' => 'Chat Status View',
                'key' => 'view_chat',
                'type' => 2,
            ],
            [
                'name' => 'On Off Chat',
                'key' => 'on_off_chat',
                'type' => 2,
            ],
            [
                'name' => 'Log File View',
                'key' => 'view_log_file',
                'type' => 4,
            ],
            [
                'name' => 'Log File Download',
                'key' => 'download_log_file',
                'type' => 4,
            ],
            [
                'name' => 'Log File Delete',
                'key' => 'delete_log_file',
                'type' => 4,
            ],
            [
                'name' => 'User Add (Web & API) Query',
                'key' => 'user_add_web_api_query',
                'type' => 2,
            ],
            [
                'name' => 'User Use Query List',
                'key' => 'view_user_query_detail',
                'type' => 2,
            ],
            [
                'name' => 'Dynamic Pricing Plan Add',
                'key' => 'add_dynamic_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Login as User',
                'key' => 'login_user_as',
                'type' => 1,
            ],
            [
                'name' => 'Feedbacks List',
                'key' => 'view_feedback_list',
                'type' => 2,
            ],
            [
                'name' => 'Feedback Delete',
                'key' => 'delete_feedback',
                'type' => 2,
            ],
            [
                'name' => 'Feedback Trash List',
                'key' => 'view_trash_feedback_list',
                'type' => 2,
            ],
            [
                'name' => 'Feedback Restore',
                'key' => 'restore_feedback',
                'type' => 2,
            ],
            [
                'name' => 'Dashboard Statistics',
                'key' => 'dashboard_stats',
                'type' => 1,
            ],
            [
                'name' => 'User Password Update',
                'key' => 'update_user_password',
                'type' => 1,
            ],
            [
                'name' => 'User Plan can Change',
                'key' => 'change_user_plan',
                'type' => 1,
            ],
            [
                'name' => 'Email Campaign Menu',
                'key' => 'email_campaign_menu',
                'type' => 3,
            ],
            [
                'name' => 'Email Campaign Add',
                'key' => 'email_campaign_add',
                'type' => 1,
            ],
            [
                'name' => 'Email Campaign View',
                'key' => 'email_campaign_view',
                'type' => 1,
            ],
            [
                'name' => 'Email Campaign Change Status',
                'key' => 'email_campaign_change_status',
                'type' => 1,
            ],
            [
                'name' => 'Email Campaign Contact List',
                'key' => 'email_campaign_user_list',
                'type' => 3,
            ],
            [
                'name' => 'Email Campaign Manage',
                'key' => 'email_campaign_manage',
                'type' => 3,
            ],
            [
                'name' => 'Email Template Add',
                'key' => 'email_template_add',
                'type' => 3,
            ],
            [
                'name' => 'Email Template View',
                'key' => 'email_template_view',
                'type' => 3,
            ],
            [
                'name' => 'Custom Page Add',
                'key' => 'custom_page_add',
                'type' => 3,
            ],
            [
                'name' => 'Custom Page View',
                'key' => 'custom_page_view',
                'type' => 3,
            ],
            [
                'name' => 'Custom Page Trash View',
                'key' => 'custom_page_trash_view',
                'type' => 3,
            ],
            [
                'name' => 'Custom Page Delete',
                'key' => 'custom_page_delete',
                'type' => 1,
            ],
            [
                'name' => 'Custom Page Restore',
                'key' => 'custom_page_restore',
                'type' => 1,
            ],
            [
                'name' => 'Custom Page Edit',
                'key' => 'custom_page_edit',
                'type' => 3,
            ],
            [
                'name' => 'Campaign Email List',
                'key' => 'email_campaign_email_list',
                'type' => 3,
            ],
            [
                'name' => 'Campaign Email List Upload',
                'key' => 'email_campaign_email_list_upload',
                'type' => 1,
            ],
            [
                'name' => 'Campaign Email List Delete',
                'key' => 'email_campaign_email_list_delete',
                'type' => 1,
            ],
            [
                'name' => 'Export Web Users',
                'key' => 'export_web_users',
                'type' => 1,
            ],
            [
                'name' => 'Custom Fields List',
                'key' => 'view_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Custom Field Add',
                'key' => 'add_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Custom Field Edit',
                'key' => 'edit_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Custom Field Trash List',
                'key' => 'trash_view_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Custom Field Delete',
                'key' => 'delete_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Custom Field Restore',
                'key' => 'restore_custom_field',
                'type' => 4,
            ],
            [
                'name' => 'Components List',
                'key' => 'view_component',
                'type' => 4,
            ],
            [
                'name' => 'Component Add',
                'key' => 'add_component',
                'type' => 4,
            ],
            [
                'name' => 'Component Edit',
                'key' => 'edit_component',
                'type' => 4,
            ],
            [
                'name' => 'Component Delete',
                'key' => 'delete_component',
                'type' => 4,
            ],
            [
                'name' => 'Components Trash List',
                'key' => 'trash_view_component',
                'type' => 4,
            ],
            [
                'name' => 'Component Restore',
                'key' => 'restore_component',
                'type' => 4,
            ],
            [
                'name' => 'Microsoft Clarity List',
                'key' => 'view_microsoft_clarity',
                'type' => 3,
            ],
            [
                'name' => 'Microsoft Clarity Add',
                'key' => 'add_microsoft_clarity',
                'type' => 3,
            ],
            [
                'name' => 'Microsoft Clarity Delete',
                'key' => 'delete_microsoft_clarity',
                'type' => 3,
            ],
            [
                'name' => 'Dynamic Pricing Plan List',
                'key' => 'view_dynamic_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Custom Pricing Plan List',
                'key' => 'view_custom_pricing_plan',
                'type' => 4,
            ],
            [
                'name' => 'Transaction Log Json Download',
                'key' => 'download_transaction_log_json',
                'type' => 1,
            ],

        ];

        foreach ($setting as $key => $value) {
            EmdPermission::updateOrCreate(
                ['key' => $value['key']],
                [
                    'name' => $value['name'],
                    'type' => $value['type'],
                ]
            );
        }
    }
}
