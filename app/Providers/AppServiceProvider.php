<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')
            || request()->isSecure()
            || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        Blade::directive('dateDateTime', function($date){
            if($date!=NULL){
                return "<?php echo date('m/d/Y @ h:i:s A', strtotime($date)); ?>";
                }else {
                    return NULL;
                }
    } );
    }
}
