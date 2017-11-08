<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CookiesToken
{


    /**
     *  Get token key from session
     *  if not exist - create
     *
     *  store in request params
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $token = Session::get('_token');

        if (!$token) {
            $token = str_random(rand(32, 40));
            Session::push('_token.0', $token);
        }

        $request->attributes->add(['token'=>$token]);

        return $next($request);
    }
}
