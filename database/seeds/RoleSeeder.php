<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            'name_role' => 'admin',
            'create' => TRUE,
            'read' => TRUE,
            'update' => TRUE,
            'delete' => TRUE,
            'created_by' => "System"
        ]);
    }
}
