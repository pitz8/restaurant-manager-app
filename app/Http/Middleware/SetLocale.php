<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // 1. Get the 'lang' from the URL {lang} parameter
        $lang = $request->route('lang');

        // 2. Define your supported languages
        $supportedLanguages = ['it', 'en', 'fr', 'de'];

        // 3. If the lang in the URL is valid, set it. 
        // Otherwise, default to the config default (probably 'it')
        if ($lang && in_array($lang, $supportedLanguages)) {
            app()->setLocale($lang);
        } else {
            app()->setLocale(config('app.locale'));
        }

        // 4. CRITICAL: This keeps the flag active in the URL for all generated links
        \Illuminate\Support\Facades\URL::defaults(['lang' => app()->getLocale()]);

        return $next($request);
    }
}
