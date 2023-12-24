<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class oAuthExceptionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);

            if (isset($response->exception) && $response->exception) {
                throw $response->exception;
            }

            return $response;
        } catch (\Exception $e) {
            return response()->json(array(
                'result' => 0,
                'msg' => $e->getMessage(),
            ), 401);
        }
    }
}

