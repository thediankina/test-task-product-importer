<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('name', 50);
            $table->string('code', 15);
            $table->decimal('price');
            $table->string('preview_text', 30);
            $table->text('detail_text');
            $table->unsignedBigInteger('user_id');
            $table->primary(['id', 'user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Откат миграции
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
