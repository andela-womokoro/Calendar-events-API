<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
            'username' => 'testuser',
            'password' => bcrypt('foobar'),
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@domain.com',
        ]);
        DB::table('users')->insert([
            'username' => 'jharis',
            'password' => bcrypt('foobar'),
            'first_name' => 'John',
            'last_name' => 'Harris',
            'email' => 'john.harris@domain.com',
        ]);
        DB::table('users')->insert([
            'username' => 'janey',
            'password' => bcrypt('foobar'),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@domain.com',
        ]);
    }
}
