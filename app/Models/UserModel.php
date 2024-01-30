<?php

namespace App\Models;

use App\Models\UserTokenModel;
use Illuminate\Database\Eloquent\Model;


class UserModel extends Model
{
    protected $table = "users";
    protected $hidden = ["password"];

    public function tokens()
    {
        return $this->hasMany(UserTokenModel::class, "user_id");
    }
}
