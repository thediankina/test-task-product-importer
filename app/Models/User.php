<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

/**
 * Модель пользователя
 */
class User extends Model
{
    /**
     * @inheritdoc
     */
    public $table = 'users';

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * Сымитировать авторизованного пользователя
     * @throws Exception
     */
    public static function authorized()
    {
        $count = User::all()->count();
        Session::put('user_id', random_int(1, $count));
    }
}
