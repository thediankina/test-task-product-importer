<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
