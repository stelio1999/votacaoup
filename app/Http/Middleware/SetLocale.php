<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Definir locale para português de Moçambique
        app()->setLocale('pt_MZ');
        
        // Configurar Carbon para português de Moçambique
        \Carbon\Carbon::setLocale('pt_MZ');
        
        return $next($request);
    }
}