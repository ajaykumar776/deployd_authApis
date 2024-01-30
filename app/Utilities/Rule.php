<?php

namespace App\Utilities;

class Rule
{
    public static function get($key, $is_required = false)
    {
        $data = [
            "id" => "integer|min:0",
            "email" => "string|email|max:255",
            "password" => "string|min:6|max:50",
            "name" => "string|max:50",
            "boolean" => "integer|in:0,1",
            "date" => "regex:/^[123][0-9]{3}-[01][0-9]-[0123][0-9]$/|max:10",
            "datetime" => "regex:/^[123][0-9]{3}-[01][0-9]-[0123][0-9] [012][0-9]:[0-5][0-9]:[0-5][0-9]$/",

        ];

        $rule = array_key_exists($key, $data) ? $data[$key] : '';

        if ($is_required) {
            $rule = "required|" . $rule;
        } else {
            $rule = "nullable|" . $rule;
        }

        return $rule;
    }
}
