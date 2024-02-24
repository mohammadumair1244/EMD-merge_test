<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class EmdSendEmailController extends Controller
{
    public static function prepostseo_api_email($email, $from, $subject, $body)
    {
        Http::timeout(30)->asForm()->post('https://apis.prepostseo.com/sendgrid-api', [
            "to" => $email,
            "subject" => $subject,
            "msg" => $body,
            "name" => $from,
            "domain" => "www.paraphraser.io",
        ]);
    }

    public static function sendBccEmail($from_email, $from_name, $from_subject, $body, $emails)
    {
        Http::post('https://phpstack-952332-3939371.cloudwaysapps.com/send-email', [
            "from_email" => @$from_email ?: 'testing@gmail.com',
            "bcc_email" => $emails,
            "from" => @$from_name ?: 'EMD Name',
            "title" => @$from_subject ?: 'EMD Subject',
            "body" => @$body ?: '<p>Emd Body</p>',
        ]);
    }
    public static function sendToEmail($from_email, $from_name, $from_subject, $body, $emails)
    {
        Http::post('https://phpstack-952332-3939371.cloudwaysapps.com/send-email', [
            "from_email" => @$from_email ?: 'testing@gmail.com',
            "to_email" => $emails,
            "from" => @$from_name ?: 'EMD Name',
            "title" => @$from_subject ?: 'EMD Subject',
            "body" => @$body ?: '<p>Emd Body</p>',
        ]);
    }
}
