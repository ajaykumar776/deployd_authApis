<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Utilities\Auth;
use App\Utilities\Output;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Middleware\UserAuthMiddleware;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;

class AuthApi extends Controller
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function login(Request $request)
    {
        try {
            $email = trim($request->input('email'));
            $password = trim($request->input('password'));
            $data = AuthService::login($email, $password);

            DB::commit();
            return Output::success(__("response.login"), $data);
        } catch (Exception $e) {
            Log::error("error: ", $e->getMessage());
            DB::rollBack();
            return Output::error($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $first_name = trim($request->input('first_name'));
            $last_name = trim($request->input('last_name'));
            $email = trim($request->input('email'));
            $password = trim($request->input('password'));
            $password_confirmation = trim($request->input('password_confirmation'));

            if ($password && $password !== $password_confirmation) throw new Exception(__("response.password_mismatch"));

            $data = [];

            $data = AuthService::register($first_name, $last_name, $email, $password);

            DB::commit();
            return Output::success(__("response.register"), $data);
        } catch (Exception $e) {
            Log::error("error: ", $e->getMessage());
            DB::rollBack();
            return Output::error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $data = new UserResource(Auth::user());
            return Output::success(__("response.get"), $data);
        } catch (Exception $e) {
            Log::error("error: ", $e->getMessage());
            return Output::error($e->getMessage());
        }
    }
}
