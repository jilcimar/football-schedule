<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        \App\User::create([
            'name'=>'Jilcimar - Admin',
            'email'=>'jilcimar.fernandes0267@gmail.com',
            'password'=>bcrypt('19911809'),
        ]);
    }
}
