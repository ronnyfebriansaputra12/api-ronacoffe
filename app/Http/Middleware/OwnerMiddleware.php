<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json([
                'data' => null,
                'message' => 'Token not provided.',
                'success' => false
            ], 401);
        }

        try {
            $credentials = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false
            ], 401);
        }

        // dd($credentials);

        if($credentials->sub == ""){
            return response()->json([
                'data' => null,
                'message' => 'Unauthorized.',
                'success' => false
            ], 401);
        }

        $user = User::where('user_id', $credentials->sub)->first();
        if(! $user){
            return response()->json([
                'data' => null,
                'message' => 'Unauthorized.',
                'success' => false
            ], 401);
        }

        // dd($user);

        if($user->role != "superadmin" && $user->role != "admin"){
            return response()->json([
                'data' => null,
                'message' => 'Unauthorized.',
                'success' => false
            ], 401);
        }


        $request->auth = $user;


        return $next($request);
    }
}
