<?php

namespace Stmik\Http\Middleware;

use Closure;
use Stmik\User;

class TokenCheck
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
        if($request->route('token') == null || $request->route('token') == ""){
            return response()->json(array("message" => "tolong sertakan token dalam url parameter anda !"), 403);	
        } else {
            $tokenCheck = User::where([
                "remember_token" => $request->route('token'),])->first();
            if($tokenCheck == null){
                return response()->json(array(
                    "message" => "Maaf! tidak ditemukan user berdasarkan token yang anda berikan"
                ), 403);
            }
        }

        return $next($request);
    }
}
