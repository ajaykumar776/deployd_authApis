<?php

namespace App\Services;

use App\Models\Enums\FlagEnum;
use App\Models\UserModel;
use Exception;
use App\Utilities\Auth;
use App\Utilities\Rule;
use App\Utilities\Common;
use App\Services\UserService;
use App\Utilities\JWTLibrary;
use App\Services\UserTokenService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

interface AuthInterface
{
    public static function login($email, $password);
    public static function register($first_name, $last_name, $email, $password);
    public static function check_password($password, $user_password);
    public static function encrypt_password($password);
}

class AuthService implements AuthInterface
{
    /**
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password
     * @return integer user id of created user
     */
    public static function register($first_name, $last_name, $email, $password)
    {
        $user_id = UserService::add_user($first_name, $last_name, $email, $password);
        $user = AuthService::login($email, $password);
        return $user;
    }


    public static function login($email, $password)
    {
        $validator = FacadesValidator::make(compact("email", "password"), [
            "email" => Rule::get("email", true),
            "password" => Rule::get("password", true)
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $user = UserService::get_by_email($email);
        if (!$user) throw new Exception(__("response.email_not_exist"));
        if (!AuthService::check_password($password, $user->password)) throw new Exception(__("response.email_password_invalid"));

        $user_id = $user->id;
        $token = UserTokenService::generate_jwt_token($user_id);
        UserTokenService::delete_expired_tokens($user_id);
        UserTokenService::insert_token_by_user_id($user_id, $token, ["ip_address" => request()->ip()]);
        UserModel::where('id', $user_id)->update(["last_login_at" => Common::now()]);

        $data = [
            "id" => $user_id,
            "email" => $user->email,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "full_name" => $user->first_name . ' ' . $user->last_name,
            "avatar" => $user->first_name[0] . "" . $user->last_name[0],
            "token" => $token
        ];
        return $data;
    }

    /**
     * @param string $password
     * @param string $user_password
     * @return boolean eg: true/false
     */
    public static function check_password($password, $user_password)
    {
        return Hash::check($password, $user_password) ? true : false;
    }

    /**
     * @param string $password
     * @return string encrypted hash password
     */
    public static function encrypt_password($password)
    {
        return bcrypt($password);
    }
}
