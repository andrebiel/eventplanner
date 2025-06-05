<?php

use App\Models\Guest;
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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Occasion::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Guest::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(GuestGroup::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
