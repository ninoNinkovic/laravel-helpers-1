<?php

namespace SebastiaanLuca\Helpers\Html;

use Collective\Html\HtmlServiceProvider as CollectiveHtmlServiceProvider;

class HtmlServiceProvider extends CollectiveHtmlServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerFormBuilder();
        $this->registerHtmlBuilder();
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'form', FormBuilder::class,
            'html', HtmlBuilder::class,
        ];
    }
    
    /**
     * Register the HTML builder instance.
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app) {
            return new HtmlBuilder($app['url'], $app['view']);
        });
        
        $this->app->alias('html', HtmlBuilder::class);
    }
    
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());
            
            return $form->setSessionStore($app['session.store']);
        });
        
        $this->app->alias('form', FormBuilder::class);
    }
}
