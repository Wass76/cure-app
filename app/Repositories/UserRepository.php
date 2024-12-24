<?php

namespace App\Repositories;

use App\Models\User;
use DB;
use Crypt;

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
            'password' => $password,
            'role' => 'client',
        ]);
    }

    public function getAllUserInfo()
    {
        return User::with('codes')
        ->get(['id', 'phoneNumber', 'password']); // Fetch only necessary fields
    }



}
