<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
        public function findByPhoneNumber($phoneNumber)
        {
            return User::where('phoneNumber', $phoneNumber)->first();
        }

    public function createClient($phoneNumber, $password)
    {
        return User::create([
            'phoneNumber' => $phoneNumber,
            'password' => Hash::make($password),
            'role' => 'client',
        ]);
    }
}
