<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUser( 
            'Example User', 
            'example.user',
            'password',
            'example.user@mail.com', 
            '62895337617550', 
            'Active', 
            'User'
        );
        $this->createUser( 
            'Example Admin', 
            'example.admin',
            'password',
            'example.admin@mail.com', 
            '62895337617551', 
            'Active', 
            'Admin'
        );
        $this->createUser( 
            'Example Instructor', 
            'example.instructor',
            'password',
            'example.instructor@mail.com', 
            '62895337617552', 
            'Active', 
            'Instructor'
        );
        $this->createUser( 
            'Example Inspector',
            'example.inspector',
            'password',
            'example.inspector@mail.com', 
            '62895337617553', 
            'Active', 
            'Inspector'
        );
    }

    protected function createUser($name, $username, $password, $email, $phone, $status, $role)
    {
        User::create([
            'name' => $name,
            'username' => $username,
            'password' => Hash::make($password),
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'role' => $role,
        ]);
    }
}
