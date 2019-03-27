<?php

use Illuminate\Database\Seeder;

class BoardFactoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('UTC');
        DB::table('board_factories')->insert([
            0 =>
                [
                    'private_key' => 'tw72B2kcAq',
                    'activated' => 'no',
                    'chip_id' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            1 =>
                [
                    'private_key' => 'Vmmi2QEX83',
                    'activated' => 'no',
                    'chip_id' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            2 =>
                [
                    'private_key' => 'jPLLnpUB6H',
                    'activated' => 'no',
                    'chip_id' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ],
            3 =>
                [
                    'private_key' => 'awKCmDbGDv',
                    'activated' => 'no',
                    'chip_id' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]
        ]);
    }
}
