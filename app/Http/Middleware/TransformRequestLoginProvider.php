<?php

namespace App\Http\Middleware;

use Closure;

class TransformRequestLoginProvider
{
    public function handle($request, Closure $next, $guard = null)
    {
        $params = $request->all();
        if(isset($params['\code'])) {
            $request->merge([
                '\code' => null,
                'code' => $params['\code']
            ]);
        }

        if(isset($params['\oauth_token'])) {
            $request->merge([
                '\oauth_token' => null,
                'oauth_token' => $params['\oauth_token']
            ]);
        }

        return $next($request);
    }
}
