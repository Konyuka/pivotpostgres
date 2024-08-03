<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
		User::insert([
            [
                'first_name'=>'Super',
                'last_name'=>'Admin',
                'username'=> 'admin',
                'email'=> 'admin@gmail.com',
                'password'=> bcrypt('admin'),
                'role_users_id'=> 1,
                'is_active'=> true,
                'contact_no'=> 0712200200
            ],
            [
                'first_name'=>'Dummy',
                'last_name'=>'Employee',
                'username'=> 'employee',
                'email'=> 'employee@gmail.com',
                'password'=> bcrypt('employee'),
                'role_users_id'=> 2,
                'is_active'=> true,
                'contact_no'=> 0712200200
            ],
            [
                'first_name'=>'Client',
                'last_name'=>'Admin',
                'username'=> 'client',
                'email'=> 'client@gmail.com',
                'password'=> bcrypt('client'),
                'role_users_id'=> 1,
                'is_active'=> true,
                'contact_no'=> 0712200200
            ],
        ]);
    }
}
