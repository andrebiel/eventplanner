<?php

use App\Models\GuestGroup;
use App\Models\Occasion;
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
        Schema::create('guest_group_occasion', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GuestGroup::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Occasion::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_group_occasion');
    }
};
