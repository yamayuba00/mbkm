<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public static function redirectTo(Request $request): string
    {
        $user = $request->user();
        return match ($user->role) {
            1 => route('admin.dashboard'),
            4 => route('student.dashboard'),
            default => route('login'),
        };
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->routes(function () {
        //     Route::middleware('web')
        //         ->group(base_path('routes/web.php'));

        //     Route::middleware('api')
        //         ->prefix('api')
        //         ->group(base_path('routes/api.php'));
        // });
    }
}
