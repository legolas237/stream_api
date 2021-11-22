<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->get('lang', config('osm.locales.en')) ;

        if(array_key_exists($lang, config('osm.locales'))){
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
