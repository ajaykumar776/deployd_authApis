<?php

namespace App\Services;

use Exception;
use App\Utilities\JWTLibrary;
use App\Models\UserTokenModel;

interface UserTokenInterface
{
    public static function insert_token_by_user_id($user_id, $token, $params = []);
    public static function generate_jwt_token($user_id);
    public static function delete_expired_tokens($user_id);
}

class UserTokenService implements UserTokenInterface
{
    public static function insert_token_by_user_id($user_id, $token, $params = [])
    {
        $ip_address = array_key_exists("ip_address", $params) ? $params['ip_address'] : '';

        UserTokenModel::insert([
            "user_id" => $user_id,
            "token" => $token,
            "ip_address" => $ip_address
        ]);
    }

    public static function generate_jwt_token($user_id)
    {

        $is_exist = false;
        do {
            $new_token = JWTLibrary::encode($user_id);
            $is_exist = UserTokenModel::where('token', $new_token)->first();
        } while ($is_exist);

        return $new_token;
    }

    public static function delete_expired_tokens($user_id)
    {
        $users = UserTokenModel::where('user_id', $user_id)->get();

        foreach ($users as $user) {
            try {
                JWTLibrary::decode($user->token);
            } catch (Exception $e) {
                UserTokenModel::where('user_id', $user_id)->where('token', $user->token)->delete();
            }
        }
    }
}
