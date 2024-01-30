<?php

namespace App\Utilities;

use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class JWTLibrary
{
    public static function encode($user_id)
    {
        $key = JWTLibrary::get_key();
        $payload = JWTLibrary::get_payload($user_id);
        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }

    public static function decode($jwt)
    {
        $key = JWTLibrary::get_key();
        try {
            $decoded = JWT::decode($jwt, $key, ['HS256']);
            return $decoded;
        } catch (\Exception $e) {
            Log::error('JWT decoding error: ' . $e->getMessage());
            return null;
        }
    }

    public static function get_payload($user_id)
    {
        return array(
            "user_id" => $user_id,
            "iat" => time(),
            "exp" => time() + 60 * 60 * 24,
        );
    }

    public static function get_key()
    {
        return env('JWT_KEY', '');
    }
}
