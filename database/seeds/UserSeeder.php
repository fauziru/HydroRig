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
            'name' => 'fauzi',
            'email' => 'fauzirezaumr@gmail.com',
            'password' => Hash::make('ultah020497'),
            'phone' => '085691207607',
            'role' => 'admin'
        ]);
        factory(App\User::class, 1)->create([
          'name' => 'tesuser',
          'email' => 'tes@gmail.com',
          'password' => Hash::make('password'),
          'phone' => '-',
          'role' => 'admin'
        ]);
        factory(App\User::class, 1)->create([
            'name' => 'msaukat',
            'email' => 'msaukat@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '-',
            'role' => 'admin'
        ]);
    }
}
