<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $users = [
            [
                'name'      =>  'user1',
                'email'     =>  'user1@email.com',
                'password'  =>  bcrypt(987654321)
            ],
            [
                'name'      =>  'user2',
                'email'     =>  'user2@email.com',
                'password'  =>  bcrypt(987654321)
            ],
            [
                'name'      =>  'user3',
                'email'     =>  'user3@email.com',
                'password'  =>  bcrypt(987654321)
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
