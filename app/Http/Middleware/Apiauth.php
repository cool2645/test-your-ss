<?php

namespace App\Http\Middleware;

use Closure;

class Apiauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isset($request->key) || $request->key != env('API_KEY'))
            return response(json_encode(['result' => false, 'msg' => "Authentication failure"]));
        return $next($request);
    }
}
