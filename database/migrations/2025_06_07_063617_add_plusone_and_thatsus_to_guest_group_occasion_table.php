<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guest_group_occasion', function (Blueprint $table) {
            $table->boolean('plus_one_allowed')->default(false);
            $table->boolean('thats_us')->default(false);
        });
    }
};
