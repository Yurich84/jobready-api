<?php

namespace Yurich84\JobReadyApi;

use Illuminate\Support\ServiceProvider;

class JobReadyServiceProvider extends ServiceProvider {

    public function boot()
    {
        //
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/jobready.php', 'jobready');

        $this->app->bind('JobReady', function(){
            return new JobReady();
        });
    }

}