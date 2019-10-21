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
        \App\Models\BackpackUser::create([
            'name' => 'Mr Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('123456')
        ]);
    }
}
