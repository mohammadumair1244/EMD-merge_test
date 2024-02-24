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
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Detail',
                'key' => 'view_user_detail',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Users Comments List',
                'key' => 'view_user_comment',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Users Transactions List',
                'key' => 'view_user_transaction',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Search',
                'key' => 'user_search',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Delete',
                'key' => 'user_delete',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'User Restore',
                'key' => 'user_restore',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'User Update',
                'key' => 'user_update',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Set Custom Premium',
                'key' => 'user_set_custom_premium',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Add Web Query',
                'key' => 'user_add_web_query',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Add API Query',
                'key' => 'user_add_api_query',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Users Trash List',
                'key' => 'view_trash_user',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Team Members List',
                'key' => 'view_team_member',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Team Member Add',
                'key' => 'team_member_add',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Team Member Edit',
                'key' => 'team_member_edit',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Team Member Detail',
                'key' => 'team_member_detail',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Team Member Delete',
                'key' => 'team_member_delete',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Team Member Restore',
                'key' => 'team_member_restore',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Team Member Trash List',
                'key' => 'view_trash_team_member',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Custom Pricing Plan Add',
                'key' => 'add_custom_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Blogs List',
                'key' => 'view_blogs',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Blog Add',
                'key' => 'add_blog',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Blog Trash List',
                'key' => 'view_trash_blog',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Blog Edit',
                'key' => 'blog_edit',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Blog Translation Btn',
                'key' => 'blog_translation_btn',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Blog Delete',
                'key' => 'blog_delete',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Blog Restore',
                'key' => 'blog_restore',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tools List',
                'key' => 'view_tool',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tool Add',
                'key' => 'add_tool',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tool Edit',
                'key' => 'edit_tool',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tool Restore',
                'key' => 'restore_tool',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tool Delete',
                'key' => 'delete_tool',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Tool Translation Btn',
                'key' => 'tool_translation_btn',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Tool Trash List',
                'key' => 'view_trash_tool',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Pricing Plans List',
                'key' => 'view_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Pricing Plan Add',
                'key' => 'add_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Pricing Plan Trash List',
                'key' => 'view_trash_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Pricing Plan Edit',
                'key' => 'edit_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Pricing Plan Delete',
                'key' => 'delete_pricing_plan',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Pricing Plan Restore',
                'key' => 'restore_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Media List',
                'key' => 'view_media',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Media Add',
                'key' => 'add_media',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Media Delete',
                'key' => 'delete_media',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Media Restore',
                'key' => 'restore_media',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Media Trash List',
                'key' => 'view_trash_media',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Contact Us List',
                'key' => 'view_contact_us',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Contact Us Delete',
                'key' => 'delete_contact_us',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Contact Us Restore',
                'key' => 'restore_contact_us',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Contact Us Trash List',
                'key' => 'view_trash_contact_us',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Key Setting List',
                'key' => 'view_setting',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Key Setting (Add & Update)',
                'key' => 'add_update_setting',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Key Setting Delete',
                'key' => 'delete_setting',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Email Settings List',
                'key' => 'view_email_setting',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Email Setting (Add & Update)',
                'key' => 'add_update_email_setting',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Transactions List',
                'key' => 'view_all_transactions',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Transaction Detail',
                'key' => 'view_transaction_detail',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Get Live Tool',
                'key' => 'get_live_tool',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Permissions List',
                'key' => 'view_all_permission_list',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Modals',
                'key' => 'view_modals',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Permission Allows',
                'key' => 'allow_permission',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Chat Status View',
                'key' => 'view_chat',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'On Off Chat',
                'key' => 'on_off_chat',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Log File View',
                'key' => 'view_log_file',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Log File Download',
                'key' => 'download_log_file',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Log File Delete',
                'key' => 'delete_log_file',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'User Add (Web & API) Query',
                'key' => 'user_add_web_api_query',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'User Use Query List',
                'key' => 'view_user_query_detail',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Dynamic Pricing Plan Add',
                'key' => 'add_dynamic_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Login as User',
                'key' => 'login_user_as',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Feedbacks List',
                'key' => 'view_feedback_list',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Feedback Delete',
                'key' => 'delete_feedback',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Feedback Trash List',
                'key' => 'view_trash_feedback_list',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Feedback Restore',
                'key' => 'restore_feedback',
                'type' => EmdPermission::SUPPORT,
            ],
            [
                'name' => 'Dashboard Statistics',
                'key' => 'dashboard_stats',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'User Password Update',
                'key' => 'update_user_password',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'User Plan can Change',
                'key' => 'change_user_plan',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Email Campaign Menu',
                'key' => 'email_campaign_menu',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Email Campaign Add',
                'key' => 'email_campaign_add',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Email Campaign View',
                'key' => 'email_campaign_view',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Email Campaign Change Status',
                'key' => 'email_campaign_change_status',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Email Campaign Contact List',
                'key' => 'email_campaign_user_list',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Email Campaign Manage',
                'key' => 'email_campaign_manage',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Email Template Add',
                'key' => 'email_template_add',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Email Template View',
                'key' => 'email_template_view',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Custom Page Add',
                'key' => 'custom_page_add',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Custom Page View',
                'key' => 'custom_page_view',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Custom Page Trash View',
                'key' => 'custom_page_trash_view',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Custom Page Delete',
                'key' => 'custom_page_delete',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Custom Page Restore',
                'key' => 'custom_page_restore',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Custom Page Edit',
                'key' => 'custom_page_edit',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Campaign Email List',
                'key' => 'email_campaign_email_list',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Campaign Email List Upload',
                'key' => 'email_campaign_email_list_upload',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Campaign Email List Delete',
                'key' => 'email_campaign_email_list_delete',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Export Web Users',
                'key' => 'export_web_users',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'Custom Fields List',
                'key' => 'view_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Field Add',
                'key' => 'add_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Field Edit',
                'key' => 'edit_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Field Trash List',
                'key' => 'trash_view_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Field Delete',
                'key' => 'delete_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Field Restore',
                'key' => 'restore_custom_field',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Components List',
                'key' => 'view_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Component Add',
                'key' => 'add_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Component Edit',
                'key' => 'edit_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Component Delete',
                'key' => 'delete_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Components Trash List',
                'key' => 'trash_view_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Component Restore',
                'key' => 'restore_component',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Microsoft Clarity List',
                'key' => 'view_microsoft_clarity',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Microsoft Clarity Add',
                'key' => 'add_microsoft_clarity',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Microsoft Clarity Delete',
                'key' => 'delete_microsoft_clarity',
                'type' => EmdPermission::PRODUCT_MANAGER,
            ],
            [
                'name' => 'Dynamic Pricing Plan List',
                'key' => 'view_dynamic_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Custom Pricing Plan List',
                'key' => 'view_custom_pricing_plan',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'Transaction Log Json Download',
                'key' => 'download_transaction_log_json',
                'type' => EmdPermission::ADMIN,
            ],
            [
                'name' => 'View Migrate Status',
                'key' => 'view_migrate_status',
                'type' => EmdPermission::DEVELOPER,
            ],
            [
                'name' => 'View Random Generated Users',
                'key' => 'view_random_users',
                'type' => EmdPermission::DEVELOPER,
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
