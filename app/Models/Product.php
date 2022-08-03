<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель товара
 */
class Product extends Model
{
    /**
     * @inheritdoc
     */
    public $table = 'products';

    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    public $fillable = [
        'id',
        'name',
        'code',
        'price',
        'preview_text',
        'detail_text',
        'user_id',
    ];
}
