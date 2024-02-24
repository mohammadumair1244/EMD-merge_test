<?php

namespace App\Console\Commands;

use App\Http\Controllers\EmdSendEmailController;
use App\Models\EmdEmailCampaign;
use App\Models\EmdEmailList;
use App\Repositories\EmdEmailCampaignRepository;
use Illuminate\Console\Command;

class SendCampaignEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendcampaign:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Campaign Email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email_campaigns = EmdEmailCampaign::with('emd_email_template')->where('status', 1)->where('start_date', '<=', date("Y-m-d"))->get();
        foreach ($email_campaigns as $email_campaign) {
            $emails = EmdEmailCampaignRepository::get_email_with_user_status($email_campaign->user_status);
            $title_array = explode(',', $email_campaign->user_status);
            $second_emails = EmdEmailList::select('email')->whereIn('title', $title_array)->get()->pluck('email')->toArray();
            if ($emails != "") {
                $emails = $emails . "," . implode(',', $second_emails);
            } else {
                $emails = implode(',', $second_emails);
            }
            if ($emails != "") {
                $emails = explode(',', $emails);
                $emails = array_slice($emails, $email_campaign->send_emails, $email_campaign->per_hour_emails);
                $email_campaign->increment('send_emails', count($emails));
                if ($email_campaign->per_hour_emails > count($emails)) {
                    $email_campaign->status = 2;
                }
                $email_campaign->save();
                EmdSendEmailController::sendBccEmail(@$email_campaign->from_email, @$email_campaign->from_name, @$email_campaign->from_subject, @$email_campaign->emd_email_template->body, implode(',', $emails));
            } else {
                $email_campaign->status = 2;
                $email_campaign->save();
            }
        }
        return Command::SUCCESS;
    }
}
