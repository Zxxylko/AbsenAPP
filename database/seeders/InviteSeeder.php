<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invite;
use Illuminate\Support\Str;

class InviteSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Invite::create([
                'code' => Str::upper(Str::random(6)),
                'used' => false,
            ]);
        }
    }
}
