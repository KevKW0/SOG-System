<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'photo' => '/img/profile.jpg',
                'role' => 'Administrator'
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@gmail.com',
                'password' => bcrypt('supervisor123'),
                'photo' => '/img/profile.jpg',
                'role' => 'Supervisor'
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@gmail.com',
                'password' => bcrypt('operator123'),
                'photo' => '/img/profile.jpg',
                'role' => 'Operator'
            ]
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}
