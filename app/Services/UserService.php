<?php

namespace App\Services;

use Exception;
use App\Utilities\Rule;
use App\Models\UserModel;
use App\Utilities\Common;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;

interface UserInterface
{
    public static function get_by_id($id);
    public static function get_by_email($email);
    public static function add_user($first_name, $last_name, $email, $password, $params = []);
    public static function add($first_name, $last_name, $email, $password, $params = []);
}

class UserService implements UserInterface
{
    /**
     * @param integer $id
     * @return object user object
     */
    public static function get_by_id($id)
    {
        return UserModel::where('id', $id)->first();
    }

    /**
     * @param string $email
     * @return object user object
     */
    public static function get_by_email($email)
    {
        return UserModel::where('email', $email)->first();
    }


    /**
     * This function will use to create a user
     * @param string $first_name
     * @param string $last_name 
     * @param string $email
     * @param string $password
     * @param array $params optiional array to pass some extra data
     * @return integer user_id of created user
     */
    public static function  add_user($first_name, $last_name, $email, $password, $params = [])
    {
        return UserService::add($first_name, $last_name, $email, $password, $params);
    }

    /**
     * @param string $first_name
     * @param string $last_name 
     * @param string $email
     * @param string $password
     * @param array $params optiional array to pass some extra data
     * @return integer user_id of created user
     */
    public static function add($first_name, $last_name, $email, $password, $params = [])
    {
        $created_at = array_key_exists("created_at", $params) ? $params['created_at'] : Common::now();
        $validator = Validator::make(compact("email", "password", "first_name", "last_name", "created_at"), [
            "first_name" => Rule::get('name', true),
            "last_name" => Rule::get('name', true),
            "email"     => Rule::get('email', true),
            "password"  => Rule::get('password', true),
            "created_at" => Rule::get("datetime"),
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $user = UserService::get_by_email($email);
        if ($user) throw new Exception(__("response.email_already_exist"));
        $user_id = UserModel::insertGetId([
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "password" => AuthService::encrypt_password($password),
            'created_at' => $created_at,
        ]);
        return $user_id;
    }
}
