<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(
            [
                'name' => 'admin',
                'email' => 'admin@livable.ai',
                'password' => bcrypt('password'), // password
                'remember_token' => ''
            ]
        );
    }
}
