<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid as Generator;

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
        try {
            DB::table('roles')->insert([
                'uuid' => Generator::uuid4()->toString(),
                'name_role' => 'admin',
                'create' => TRUE,
                'read' => TRUE,
                'update' => TRUE,
                'delete' => TRUE,
                'created_by' => "System",
                'created_at' => (new \DateTime())->format("Y-m-d H:i:s"),
                'updated_at' => (new \DateTime())->format("Y-m-d H:i:s")
            ]);
            DB::table('roles')->insert([
                'uuid' => Generator::uuid4()->toString(),
                'name_role' => 'user',
                'create' => FALSE,
                'read' => TRUE,
                'update' => FALSE,
                'delete' => FALSE,
                'created_by' => "System",
                'created_at' => (new \DateTime())->format("Y-m-d H:i:s"),
                'updated_at' => (new \DateTime())->format("Y-m-d H:i:s")
            ]);
        } catch (UnsatisfiedDependencyException $e) {
            abort(500, $e->getMessage());
        }
    }
}
