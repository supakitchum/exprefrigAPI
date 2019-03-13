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
        DB::table('board_factories')->insert([
            0 =>
                [
                    'private_key' => 'tw72B2kcAq',
                    'status' => 'no',
                    'chip_id' => null,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]]
        );
    }
}
