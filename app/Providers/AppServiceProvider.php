<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


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
   public function boot()
{
    // Compartilhar configurações com todas as views
    view()->composer('*', function ($view) {
        $view->with([
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
            'currentYear' => date('Y'),
        ]);
    });
    
    // Definir locale para português de Moçambique
    app()->setLocale('pt_MZ');
    Carbon::setLocale('pt_MZ');
    
    // Configurar validação em português
    Validator::extend('moçambicano', function ($attribute, $value, $parameters, $validator) {
        // Validações específicas para Moçambique
        return true;
    });
}
}
