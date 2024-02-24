<?php

namespace Database\Seeders;

use App\Models\EmdEmailSetting;
use Illuminate\Database\Seeder;

class EmdEmailSettingSeeder extends Seeder
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
                'email_type' => EmdEmailSetting::CONTACT_EMAIL,
                'sender_email' => 'none',
                'receiver_email' => 'testing@emd.com',
                'send_from' => 'EMD Model',
                'subject' => "Contact Us",
                'template' => '<p>Contactor name @name <p>Email @email</p><p>Message: @message</p><p>@body</p></p>',
                'is_active' => 0,
            ],
            [
                'email_type' => EmdEmailSetting::FORGOT_EMAIL,
                'sender_email' => 'testing@emd.com',
                'receiver_email' => 'none',
                'send_from' => 'EMD Model',
                'subject' => "Forgot password",
                'template' => '<p>Hi @name</p><p>Here is your account reset link</p><p>click to this link @forgot_url</p>',
                'is_active' => 0,
            ],
            [
                'email_type' => EmdEmailSetting::NEW_ACCOUNT_EMAIL,
                'sender_email' => 'testing@emd.com',
                'receiver_email' => 'none',
                'send_from' => 'EMD Model',
                'subject' => "New Account",
                'template' => 'Hi @name Click to link for verify account @verify_link',
                'is_active' => 0,
            ],
            [
                'email_type' => EmdEmailSetting::DELETE_ACCOUNT,
                'sender_email' => 'testing@emd.com',
                'receiver_email' => 'none',
                'send_from' => 'EMD Model',
                'subject' => "Account Deleted",
                'template' => '<p>Hi @name</p><p>Your Account Successfully deleted</p>',
                'is_active' => 0,
            ],
            [
                'email_type' => EmdEmailSetting::CANCEL_MEMBERSHIP,
                'sender_email' => 'none',
                'receiver_email' => 'sandhyabasu202@gmail.com',
                'send_from' => 'EMD Model',
                'subject' => "Cancel Membership",
                'template' => '<p>Hi @name</p><p>account email @account_email</p><p>All transaction order no @order_no</p><p>Request for website @website plan cancel</p><p>Request for API @api plan cancel</p>',
                'is_active' => 0,
            ],
        ];
        foreach ($setting as $key => $value) {
            EmdEmailSetting::firstOrCreate(
                ['email_type' => $value['email_type']],
                [
                    'receiver_email' => $value['receiver_email'],
                    'receiver_email' => $value['receiver_email'],
                    'send_from' => $value['send_from'],
                    'subject' => $value['subject'],
                    'template' => $value['template'],
                ]
            );
        }
    }
}
