<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App\Http\Shopify\ShopifyApi;

class ShopifyApiServiceProvider extends ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = TRUE;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        $this->app->bind('ShopifyAPI', function($app, $config = FALSE){
            return new ShopifyApi($config);
        });
    }

    public function boot(){
        #$this->package('App\Http\Shopify');
        AliasLoader::getInstance()->alias('ShopifyAPI', 'App\Http\Shopify\ShopifyApi');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(){
        return ['ShopifyAPI', 'App\Http\Shopify\ShopifyApi'];
    }

}
