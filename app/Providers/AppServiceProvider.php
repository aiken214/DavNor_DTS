<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::directive('dateDateTime', function($date){
            if($date!=NULL){
                return "<?php echo date('m/d/Y @ h:i:s A', strtotime($date)); ?>";
                }else {
                    return NULL;
                }
    } );
    }
}
