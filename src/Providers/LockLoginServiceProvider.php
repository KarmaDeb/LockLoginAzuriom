<?php

namespace Azuriom\Plugin\LockLogin\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;

class LockLoginServiceProvider extends BasePluginServiceProvider {


    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot() {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerAdminNavigation();
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation() {
        return [
            'locklogin'=>[
                'name' => 'LockLogin',
                'type' => 'dropdown',
                'icon' => 'bi bi-lock',
                'items' => [
                    'locklogin.admin.home' => 'How to'
                ],
            ]
        ];
    }
}