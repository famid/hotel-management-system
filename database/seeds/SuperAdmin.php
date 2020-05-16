<?php

use Illuminate\Database\Seeder;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'first_name' => 'Mr.',
            'last_name' => 'Super Admin',
            'email' => 'superadmin@email.com',
            'user_name' => null,
            'phone' => null,
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => SUPER_ADMIN_ROLE,
            'email_verification_code' => null,
            'status' => USER_ACTIVE_STATUS
        ]);

        \App\User::create([
            'first_name' => 'Mr.',
            'last_name' => 'Famid',
            'email' => 'famid@gmail.com',
            'user_name' => null,
            'phone_code' => '+880',
            'phone' => '1793851981',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => ADMIN_ROLE,
            'email_verification_code' => null,
            'is_phone_verified' => ACTIVE_STATUS,
            'status' => USER_ACTIVE_STATUS
        ]);
        \App\User::create([
            'first_name' => 'Mr.',
            'last_name' => 'Osif',
            'email' => 'osif@gmail.com',
            'user_name' => null,
            'phone_code' => '+880',
            'phone' => '1793851982',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => USER_ROLE,
            'email_verification_code' => null,
            'is_phone_verified' => ACTIVE_STATUS,
            'status' => USER_ACTIVE_STATUS
        ]);


//        \App\User::create([
//            'first_name' => 'Mr.',
//            'last_name' => 'Admin',
//            'email' => 'admin@email.com',
//            'user_name' => null,
//            'phone' => null,
//            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
//            'role' => ADMIN_ROLE,
//            'email_verification_code' => null,
//            'status' => USER_ACTIVE_STATUS
//        ]);
//
//        \App\User::create([
//            'first_name' => 'Mr.',
//            'last_name' => 'User',
//            'email' => 'user@email.com',
//            'user_name' => null,
//            'phone' => null,
//            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
//            'role' => USER_ROLE,
//            'email_verification_code' => null,
//            'status' => USER_ACTIVE_STATUS
//        ]);
    }
}
