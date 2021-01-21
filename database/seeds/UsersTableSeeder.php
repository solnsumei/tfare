<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();
        \App\User::create([
            'name' => 'Super Admin',
            'email' => 'superAdmin@email.com',
            'password' => app('hash')->make('your_password'),
            'isAdmin' => 1,
            'role_id' => 1,
        ]);

        \App\User::create([
            'name' => 'Contributor',
            'email' => 'contributor@email.com',
            'password' => app('hash')->make('your_password'),
            'isAdmin' => 1,
            'role_id' => 2,
        ]);
    }
}
