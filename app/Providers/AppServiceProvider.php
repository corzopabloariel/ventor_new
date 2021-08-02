<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;
use App\Models\Ventor\Ventor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
            //TODO Queda así hasta que se compre el certificado posta
            //\URL::forceScheme('https');
        }
        Paginator::useBootstrap();
        $no_img = asset("images/no-img.png");
        try {
            $ventor = Ventor::first();
            view()->composer('*', function($view) use ($ventor, $no_img) {
                $view->with('ventor', $ventor);
                $view->with('no_img', $no_img);
            });
        } catch (\Throwable $th) {}
    }
}
