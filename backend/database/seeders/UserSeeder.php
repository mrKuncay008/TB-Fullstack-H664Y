<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Pren Jon', 'email' => 'prenundira@gmail.com', 'password' => Hash::make('admin12345')],
            ['name' => 'Admin', 'email' => 'user.admin@gmail.com', 'password' => Hash::make('admin12345')],
            ['name' => 'kelurahan Cipete', 'email' => 'kelurahancipete@gmail.com', 'password' => Hash::make('admin12345')],
            ['name' => 'kelurahan Greenlake', 'email' => 'kelurahangreenlake@gmail.com', 'password' => Hash::make('admin12345')],
            
        ]);
    }
}
