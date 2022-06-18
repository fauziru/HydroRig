<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('settings')->insert([
                'registrasi_key' => '-',
                'api_key' => '-',
                'layout_id' => null,
                'updated_by' => 'system',
                'version' => 'v1.1',
                'is_maintenance' => 0,
                'created_at' => (new \DateTime())->format("Y-m-d H:i:s"),
                'updated_at' => (new \DateTime())->format("Y-m-d H:i:s")
            ]);
        } catch (UnsatisfiedDependencyException $e) {
            abort(500, $e->getMessage());
        }
    }
}
