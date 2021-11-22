<?php

namespace Database\Seeders;

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
        \App\Models\User::factory(1)->create([
            'user_detail_id' => function () {
                return \App\Models\UserDetail::factory(1)->create()->first->{'id'};
            }
        ]);
    }
}
