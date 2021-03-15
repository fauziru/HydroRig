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
            'name' => 'eumbeu',
            'email' => 'eumbeu@urbarn.com',
            'password' => Hash::make('ultah020497'),
            'phone' => '085691207607',
            'role' => 'admin'
        ]);
        factory(App\User::class, 1)->create([
          'name' => 'ramdan',
          'email' => 'ramdan@urBarn.com',
          'password' => Hash::make('password'),
          'phone' => '-',
          'role' => 'admin'
      ]);
        factory(App\User::class, 1)->create();
    }
}
