<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private array $users = [
        [
            "name" => "John Doe",
            "email" => "doe@gmail.com",
            "password" => "password",
            "is_admin" => true
        ],
        [
            "name" => "Alex",
            "email" => "alex@gmail.com",
            "password" => "password",
            "is_admin" => false
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name','admin')->first();

        foreach ($this->users as $user) {
            $userModel = new User();
            $userModel->name = $user["name"];
            $userModel->email = $user["email"];
            $userModel->password = bcrypt($user["password"]);
            $userModel->save();
            if ($user["is_admin"]) {
                $userModel->roles()->attach($adminRole);
            }
        }
    }
}
