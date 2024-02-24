<?php

namespace App\Providers;

use App\Interfaces;
use App\Repositories;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        $this->app->bind(Interfaces\EmdPricingPlanInterface::class, Repositories\EmdPricingPlanRepository::class);
        $this->app->bind(Interfaces\EmdPricingPlanAllowInterface::class, Repositories\EmdPricingPlanAllowRepository::class);
        $this->app->bind(Interfaces\EmdWebUserInterface::class, Repositories\EmdWebUserRepository::class);
        $this->app->bind(Interfaces\EmdUserTransactionInterface::class, Repositories\EmdUserTransactionRepository::class);
        $this->app->bind(Interfaces\EmdEmailSettingInterface::class, Repositories\EmdEmailSettingRepository::class);
        $this->app->bind(Interfaces\EmdUserProfileCommentInterface::class, Repositories\EmdUserProfileCommentRepository::class);
        $this->app->bind(Interfaces\EmdPlanZonePriceInterface::class, Repositories\EmdPlanZonePriceRepository::class);
        $this->app->bind(Interfaces\EmdPermissionInterface::class, Repositories\EmdPermissionRepository::class);
        $this->app->bind(Interfaces\EmdUserPermissionInterface::class, Repositories\EmdUserPermissionRepository::class);
        $this->app->bind(Interfaces\EmdFeedbackInterface::class, Repositories\EmdFeedbackRepository::class);
        $this->app->bind(Interfaces\EmdEmailCampaignInterface::class, Repositories\EmdEmailCampaignRepository::class);
        $this->app->bind(Interfaces\EmdEmailTemplateInterface::class, Repositories\EmdEmailTemplateRepository::class);
        $this->app->bind(Interfaces\EmdCustomPageInterface::class, Repositories\EmdCustomPageRepository::class);
        $this->app->bind(Interfaces\EmdEmailListInterface::class, Repositories\EmdEmailListRepository::class);
        $this->app->bind(Interfaces\EmdCustomFieldInterface::class, Repositories\EmdCustomFieldRepository::class);
        $this->app->bind(Interfaces\EmdComponentInterface::class, Repositories\EmdComponentRepository::class);
        $this->app->bind(Interfaces\EmdMicrosoftClarityInterface::class, Repositories\EmdMicrosoftClarityRepository::class);
        $this->app->bind(Interfaces\EmdTransactionLogInterface::class, Repositories\EmdTransactionLogRepository::class);
    }
}
