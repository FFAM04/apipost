<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            // dd($e);
            
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $code = 401;
                $result['error'] = 'Unauthorization, Token is Invalid';
                return response()->json($result, $code);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $code = 401;
                $result['error'] = 'Token is Expired';
                return response()->json($result, $code);
            }else{
                $code = 401;
                $result['error'] = 'Authorization Token not found';
                return response()->json($result, $code);
            }
        }
        return $next($request);
    }
}