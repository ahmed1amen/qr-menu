<?php

namespace App\Providers;

use App\Settings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //ignore default migrations from Cashier
        Cashier::ignoreMigrations();

        /* $this->app->bind(
             \Auth0\Login\Contract\Auth0UserRepository::class,
             \App\Repositories\CustomUserRepository::class
         );*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::bootstrap-4');
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
        try {
            \DB::connection()->getPdo();
            $settings = Schema::hasTable('settings') && Settings::find(1) ? Settings::find(1)->toArray() : [];

            //Site logo
            if ((isset($settings['site_logo']) && ! (strpos($settings['site_logo'], '/') !== false))) {
                $settings['site_logo'] = '/uploads/settings/'.$settings['site_logo'].'_logo.jpg';
            }

            //Site logo dark
            if ((isset($settings['site_logo_dark']) && ! (strpos($settings['site_logo_dark'], '/') !== false))) {
                $settings['site_logo_dark'] = '/uploads/settings/'.$settings['site_logo_dark'].'_site_logo_dark.jpg';
            }

            //Search
            if ((isset($settings['search']) && ! (strpos($settings['search'], '/') !== false))) {
                $settings['search'] = '/uploads/settings/'.$settings['search'].'_cover.jpg';
            }

            //Details default cover image
            if ((isset($settings['restorant_details_cover_image']) && ! (strpos($settings['restorant_details_cover_image'], '/') !== false))) {
                $settings['restorant_details_cover_image'] = '/uploads/settings/'.$settings['restorant_details_cover_image'].'_cover.jpg';
            }

            //Restaurant default image
            if ((isset($settings['restorant_details_image']) && ! (strpos($settings['restorant_details_image'], '/') !== false))) {
                $settings['restorant_details_image'] = '/uploads/settings/'.$settings['restorant_details_image'].'_large.jpg';
            }

            config([
                'global' =>  $settings,
            ]);

            $moneyList=[];
            $rawMoney=config('money');
            foreach ($rawMoney as $key => $value) {
                $moneyList[$key]=$value['name']." - ".$value['symbol']." - ".$key;
            }

            //Setup for money list
            config(['config.env.2' =>  [
                'name'=>'Localization',
                'slug'=>'localizatino',
                'icon'=>'ni ni-world-2',
                'fields'=>[
                    ['title'=>'Default language', '', 'key'=>'APP_LOCALE', 'value'=>'en', 'ftype'=>'select', 'data'=>config('languages')],
                    ['title'=>'List of available language on the landing page', 'help'=>'Define a list of Language shortcode and the name. If only one language is listed, the language picker will not show up', 'key'=>'FRONT_LANGUAGES', 'value'=>'EN,English,FR,French', 'onlyin'=>'qrsaas'],
                    ['title'=>'Time zone', 'help'=>'This value is important for correct vendors opening and closing times', 'key'=>'TIME_ZONE', 'value'=>'Europe/Berlin', 'ftype'=>'select', 'data'=>config('timezones')],
                    ['title'=>'Default currency', 'key'=>'CASHIER_CURRENCY', 'value'=>'eur', 'ftype'=>'select', 'data'=>$moneyList],
                    ['title'=>'Money conversion', 'help'=>'Some currencies need this field to be unselected. By default it should be selected', 'key'=>'DO_CONVERTION', 'value'=>'true', 'ftype'=>'bool'],
                    ['title'=>'Time format', 'key'=>'TIME_FORMAT', 'value'=>'AM/PM', 'ftype'=>'select', 'data'=>['AM/PM'=>'AM/PM', '24hours '=>'24 Hours']],
                    ['title'=>'Date and time display', 'key'=>'DATETIME_DISPLAY_FORMAT', 'value'=>'d M Y h:i A'],
    
                ],
            ]]);
        } catch (\Exception $e) {
            //return redirect()->route('LaravelInstaller::welcome');
        }
    }
}
