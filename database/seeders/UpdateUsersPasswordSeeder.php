<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUsersPasswordSeeder extends Seeder
{
    public function run()
    {
        // Fetch all users from the `newdts` database
        $users = DB::table('users')->get();

        foreach ($users as $user) {
             //$passWord = 'pass6789$6Hi3W';
             $passWord = 'pass6789';

            // Hash the email using bcrypt
            $hashedPassword = Hash::make($passWord);

            // Update the user's password
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => $hashedPassword,
                    'password_needchange' => true,
                    'updated_at' => NULL, // Optionally update the `updated_at` timestamp
                ]);
        }
    }
}
