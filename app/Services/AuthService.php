<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\CodeRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;
    protected $codeRepository;

    public function __construct(UserRepository $userRepository, CodeRepository $codeRepository)
    {
        $this->userRepository = $userRepository;
        $this->codeRepository = $codeRepository;
    }

    public function handleRegisterOrLogin($data)
    {
        $user = $this->userRepository->findByPhoneNumber($data['phone_number']);

        if ($user) {
            // echo 1;

            if ($this->authenticateUser($user, $data['password'], $data['code'])) {
                $response['message'] = 'Client registered successfully';
                $response['token'] = $user->createToken('client-token')->accessToken;
                return [
                    'data' => $response,
                    'status' => 200,
                ];
            } else {
                return [
                    'data' => ['message' => 'Invalid credentials or code'],
                    'status' => 401,
                ];
            }
        } else {
            return $this->registerNewClient($data);
        }
    }

    private function authenticateUser($user, $password, $code)
    {
        return Hash::check($password, $user->password) &&
               $this->codeRepository->isCodeValidForUser($code, $user->id);
    }

    public function authenticateAdmin($data) {
        // Access the phone_number from the array correctly
        // echo $data['phone_number'];
        // if ($data['phone_number'] == 'admin' || $data['phone_number'] == 'SuperAdmin') {
            $user = $this->userRepository->findByPhoneNumber($data['phone_number']);
            // if($user){
            //     echo $user->phoneNumber;
            // }

            // Check if the user exists before verifying the password
            $password = $data['password'];
            // echo $password;
            if ($user && Hash::check($password, $user->password)) {
                // echo $user->phoneNumber;
                $response['message'] = 'Admin login successfully';
                $response['token'] = $user->createToken('admin-token')->accessToken;
                return [
                    'data' => $response,
                    'status' => 200,
                ];
            }
        // }
        // Handle invalid credentials
        return [
            'data' => ['message' => 'Invalid credentials or unauthorized access'],
            'status' => 401,
        ];
    }


    private function registerNewClient($data)
    {
        $code = $this->codeRepository->findValidCode($data['code']);

        if (!$code) {
            return [
                'data' => ['message' => 'Invalid or already used code'],
                'status' => 400,
            ];
        }

        $user = $this->userRepository->createClient($data['phone_number'], $data['password']);
        $this->codeRepository->assignCodeToUser($code, $user->id);

        $response['message'] = 'Client registered successfully';
        $response['token'] = $user->createToken('client-token')->accessToken;

        return [
            'data' => $response,
            'status' => 201,
        ];
    }
}
