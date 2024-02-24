<?php

namespace App\Listeners;

use App\Http\Controllers\EmdMobilePaymentRenewalController;
use Imdhemy\Purchases\Events\GooglePlay\SubscriptionRenewed;

class GooglePlayRenewalListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */

    public function handle(SubscriptionRenewed $event)
    {
        $subscription = $event->getServerNotification()->getSubscription();
        $uniqueIdentifier = $subscription->getUniqueIdentifier();
        $expirationTime = $subscription->getExpiryTime();
        $pid = $subscription->getItemId();
        $res = EmdMobilePaymentRenewalController::GoogleSubscriptionRenewal($pid, $uniqueIdentifier);
    }
}
