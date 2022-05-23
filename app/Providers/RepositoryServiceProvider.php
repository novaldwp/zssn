<?php

namespace App\Providers;

use App\Interfaces\ContaminationSurvivorRepositoryInterface;
use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\SurvivorRepositoryInterface;
use App\Interfaces\TradeRepositoryInterface;
use App\Repositories\ContaminationSurvivorRepository;
use App\Repositories\ItemRepository;
use App\Repositories\SurvivorRepository;
use App\Repositories\TradeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SurvivorRepositoryInterface::class, SurvivorRepository::class);
        $this->app->bind(ContaminationSurvivorRepositoryInterface::class, ContaminationSurvivorRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(TradeRepositoryInterface::class, TradeRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
