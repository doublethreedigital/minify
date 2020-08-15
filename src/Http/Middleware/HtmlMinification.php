<?php

namespace DoubleThreeDigital\Minify\Http\Middleware;

use Closure;
use DoubleThreeDigital\Minify\Minify;

class HtmlMinification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->route()->getName() === 'statamic.site') {
            $response = $next($request);

            $response->setContent(
                Minify::html($response->content())
            );

            return $response;
        }

        return $next($request);
    }
}
