<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operators_type = ['operator simpan pinjam', 'operator foto copy', 'operator brilink'];
        foreach ($operators_type as $key => $value) {
            User::factory()->create([
                'name' => $operators_type[$key],
                'email' => Str::slug(str_replace('operator', '', $operators_type[$key])) . '@bumdes.com',
                'email_verified_at' => now(),
                'role' => $operators_type[$key],
                'password' => bcrypt('password'),
            ]);
        }
    }
}
