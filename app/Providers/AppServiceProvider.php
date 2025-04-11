<?php

namespace App\Providers;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
        //
        Relation::enforceMorphMap([
            'adjustments' => 'App\Models\Adjustment',
            // Add others later, e.g., 'returntosupplier' => 'App\Models\ReturnToSupplier'
        ]);

        Product::observe(ProductObserver::class);
    }
}
