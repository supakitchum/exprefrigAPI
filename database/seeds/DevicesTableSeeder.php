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
                        'refrig_id' => 'admin',
                        'name' => null,
                        'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    ]]
        );
    }
}
