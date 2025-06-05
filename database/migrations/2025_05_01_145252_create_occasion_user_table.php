<?php

use App\Enums\OccasionRole;
use App\Models\Occasion;
use App\Models\User;
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
        Schema::create('occasion_user', function (Blueprint $table) {
            $table->foreignIdFor(Occasion::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->primary(['occasion_id', 'user_id']);
            $table->timestamps();
            $table->string('role')->default(OccasionRole::ADMIN->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occasion_user');
    }
};
