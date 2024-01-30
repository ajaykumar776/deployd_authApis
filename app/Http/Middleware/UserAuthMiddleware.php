<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Exception;
use App\Utilities\Output;
use App\Utilities\JWTLibrary;
use App\Models\UserTokenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserAuthMiddleware
{

    public function handle($request, Closure $next)
    {
        try {

            $token = $request->input('token');
            if (!$token) {
                throw new Exception("Token is missing");
            }
            $jwt = JWTLibrary::decode($token);
            $user_id = $jwt->user_id;

            $user = UserModel::find($user_id);

            if (!$user) {
                throw new Exception(__("response.unauthorized_user"));
            }

            session()->put('user_id', $user_id);
            session()->put('token', $token);

            return $next($request);
        } catch (Exception $e) {
            Log::error('Exception occurred in UserAuthMiddleware', ['exception' => $e->getMessage(), 'request' => $request->all()]);
            return Output::unauthorize_user();
        }
    }
}
