<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;

class UserTokenModel extends Model
{
    protected $table = "user_tokens";

    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
}
