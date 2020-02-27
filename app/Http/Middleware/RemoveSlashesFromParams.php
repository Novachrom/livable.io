<?php

namespace App\Http\Middleware;

use Closure;

class RemoveSlashesFromParams
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
        $params = $request->all();
        foreach ($request->request->all() as $key => $value) {
            $newKey = ltrim($key, '\\');
            $request->request->remove($key);
            $request->request->set($newKey, $value);
        }

        return $next($request);
    }
}
