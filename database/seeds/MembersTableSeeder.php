<?php

use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->insert([
                0 =>
                    [
                        'email' => 'admin',
                        'password' => app('hash')->make('1234'),
                        'name' => 'admin',
                        'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    ]
            ]
        );
    }
}
