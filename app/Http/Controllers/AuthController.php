<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function registerOrLogin(Request $request)
    {
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'password' => 'required|mac_address',  // MAC address
            'code' => 'required',
        ]);

        $response = $this->authService->handleRegisterOrLogin($validatedData);

        return response()->json($response['data'], $response['status']);
    }

    public function loginForAdmin(Request $request){
        $validatedData = $request->validate([
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        // echo $validatedData['phone_number'];

        $response = $this->authService->authenticateAdmin($validatedData);

        return response()->json($response['data'], $response['status']);

    }

    public function getAllUserInfo()
    {
        // Call the service layer
        $userInfo = $this->authService->getAllUserInfo();

        // Return a JSON response
        return response()->json($userInfo);
    }
}
