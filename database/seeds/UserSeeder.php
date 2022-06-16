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
        factory(App\User::class, 1)->create([
            'name_user' => 'fauzi',
            'email' => 'fauzirezaumr@gmail.com',
            'password' => Hash::make('ultah020497'),
            'phone' => '085691207607',
            'role_id' => 1
        ]);
        factory(App\User::class, 1)->create([
          'name_user' => 'tesuser',
          'email' => 'tes@gmail.com',
          'password' => Hash::make('password'),
          'phone' => '-',
          'role_id' => 2
        ]);
        factory(App\User::class, 1)->create([
            'name_user' => 'msaukat',
            'email' => 'msaukat@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '-',
            'role_id' => 1
        ]);
    }
}
