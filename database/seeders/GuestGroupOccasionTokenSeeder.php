<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestGroupOccasionTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guest_group_occasion')->whereNull('invite_token')->cursor()->each(function ($item) {
            DB::table('guest_group_occasion')
                ->where('id', $item->id)
                ->update(['invite_token' => Str::random(32)]);
        });
    }
}
