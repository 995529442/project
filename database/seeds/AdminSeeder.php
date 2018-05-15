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
        DB::table("admins")->insert([
           'username' => 'admin',
           'password' => bcrypt('123456'),
           'type'     => 1
        ]);
    }
}
