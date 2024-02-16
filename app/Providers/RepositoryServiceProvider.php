<?php

namespace App\Providers;

use App\Models\Category;
use App\Repository\Shift\ShiftRepository;
use App\Repository\Shift\ShiftRepositoryInterface;
use App\Repository\Category\CategoryRepository;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Discount\DiscountRepository;
use App\Repository\Discount\DiscountRepositoryInterface;
use App\Repository\Item\ItemRepository;
use App\Repository\Item\ItemRepositoryInterface;
use App\Repository\Setting\SettingRepository;
use App\Repository\Setting\SettingRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\Order\OrderRepository;
use App\Repository\Order\OrderRepositoryInterface;
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
        $this->app->bind(ShiftRepositoryInterface::class, ShiftRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(DiscountRepositoryInterface::class, DiscountRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

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
