<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\CodeRepository;
use Illuminate\Support\Facades\Crypt;

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
        // Decrypt the password for comparison
        // $decryptedPassword = Crypt::decryptString($user->password);

        return $user->password === $password &&
               $this->codeRepository->isCodeValidForUser($code, $user->id);
    }

    public function authenticateAdmin($data)
    {
        $user = $this->userRepository->findByPhoneNumber($data['phone_number']);
        $password = $data['password'];

        if ($user) {
            // Decrypt the password for comparison
            // $decryptedPassword = Crypt::decryptString($user->password);

            if ($user->password === $password) {
                $response['message'] = 'Admin login successfully';
                $response['token'] = $user->createToken('admin-token')->accessToken;
                return [
                    'data' => $response,
                    'status' => 200,
                ];
            }
        }

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

        // Encrypt the password before saving
        $user = $this->userRepository->createClient(
            $data['phone_number'],
            $data['password']
        );

        $this->codeRepository->assignCodeToUser($code, $user->id);

        $response['message'] = 'Client registered successfully';
        $response['token'] = $user->createToken('client-token')->accessToken;

        return [
            'data' => $response,
            'status' => 201,
        ];
    }

    public function getAllUserInfo()
    {
        // Fetch data from the repository
        $users = $this->userRepository->getAllUserInfo();

        // Format the data for response
        return $users->map(function ($user) {
            return [
                'phoneNumber' => $user->phoneNumber,
                'mac_address' => $user->password,
                'codes' => $user->codes->map(function ($code) {
                    return [
                        'id' => $code->id,
                        'activation_code' => $code->activation_code,
                    ];
                }),
            ];
        });
    }
}
