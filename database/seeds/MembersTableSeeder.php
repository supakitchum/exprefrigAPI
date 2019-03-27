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
                        'password' => '1234',
                        'name' => 'admin',
                    ]
            ]
        );
    }
}
