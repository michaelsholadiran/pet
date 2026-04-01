<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCountryCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $name = config('puppiary.country_cookie', 'puppiary_country');
        if (! $request->cookie($name)) {
            $country = $request->header('CF-IPCountry');
            if ($country && preg_match('/^[A-Z]{2}$/', $country)) {
                $cookie = cookie($name, $country, 365 * 24 * 60, '/', null, $request->secure(), false, false, 'lax');

                return $next($request)->withCookie($cookie);
            }
        }

        return $next($request);
    }
}
