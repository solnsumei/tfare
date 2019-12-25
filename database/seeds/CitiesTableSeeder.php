<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cities')->delete();
        
        DB::table('cities')->insert([
            ['name' => 'Lagos', 'country' => 'Nigeria'],
            ['name' => 'Abuja', 'country' => 'Nigeria'],
            ['name' => 'Port-Harcourt', 'country' => 'Nigeria'],
            ['name' => 'Calabar', 'country' => 'Nigeria'],
            ['name' => 'Asaba', 'country' => 'Nigeria'],
            ['name' => 'Onitsha', 'country' => 'Nigeria'],
            ['name' => 'Owerri', 'country' => 'Nigeria'],
            ['name' => 'Enugu', 'country' => 'Nigeria'],
            ['name' => 'Awka', 'country' => 'Nigeria'],
            ['name' => 'Benin', 'country' => 'Nigeria'],
            ['name' => 'Lokoja', 'country' => 'Nigeria'],
            ['name' => 'Osogbo', 'country' => 'Nigeria'],
            ['name' => 'Abeokuta', 'country' => 'Nigeria'],
            ['name' => 'Kaduna', 'country' => 'Nigeria'],
            ['name' => 'Kano', 'country' => 'Nigeria'],
            ['name' => 'Sokoto', 'country' => 'Nigeria'],
            ['name' => 'Warri', 'country' => 'Nigeria'],
            ['name' => 'Aba', 'country' => 'Nigeria'],
            ['name' => 'Abakaliki', 'country' => 'Nigeria'],
            ['name' => 'Ekpoma', 'country' => 'Nigeria']
        ]);
    }
}
