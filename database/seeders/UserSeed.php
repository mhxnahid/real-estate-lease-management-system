<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            ['id' => 1, 'name' => 'Admin', 'email' => 'admin@admin.com', 'password' => 'password', 'remember_token' => '',],

        ];

        foreach ($items as $item) {
            \App\Models\User::firstOrCreate(['email' => $item['email']], $item);
        }
    }
}
