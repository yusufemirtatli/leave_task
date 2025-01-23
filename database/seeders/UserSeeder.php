<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Yusuf Emir Tatlı',
            'email' => 'yusufemirtatli96@gmail.com',
            'password' => Hash::make('emir1234'),
        ]);
    }
}
