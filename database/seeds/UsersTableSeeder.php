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
            'name' => 'Solomon Nsumei',
            'email' => 'solnsumei@gmail.com',
            'password' => app('hash')->make('solomon1'),
            'isAdmin' => 1,
            'role_id' => 1,
        ]);

        \App\User::create([
            'name' => 'Solomon Ejiro',
            'email' => 'solmeiweb@gmail.com',
            'password' => app('hash')->make('solomon1'),
            'isAdmin' => 1,
            'role_id' => 2,
        ]);
    }
}
