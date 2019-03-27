<?php

use Illuminate\Database\Seeder;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->insert([
         0 =>
                [
                    'private_key' => 'tw72B2kcAq',
                    'refrig_id' => null,
                    'name' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            1 =>
                [
                    'private_key' => 'Vmmi2QEX83',
                    'refrig_id' => null,
                    'name' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            2 =>
                [
                    'private_key' => 'jPLLnpUB6H',
                    'refrig_id' => null,
                    'name' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            3 =>
                [
                    'private_key' => 'awKCmDbGDv',
                    'refrig_id' => null,
                    'name' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]
        ]);
    }
}
