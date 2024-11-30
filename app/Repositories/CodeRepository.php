<?php

namespace App\Repositories;

use App\Models\Code;
use App\Exceptions\ModelNotFoundException;

class CodeRepository
{
    public function getAll()
    {
        return Code::all();
    }

    public function findById($id)
    {
        return Code::find($id);
    }

    public function findValidCode($code)
    {
        return Code::where('activation_code', $code)->where('is_taken', false)->first();
    }

    public function isCodeValidForUser($code, $userId)
    {
        return Code::where('activation_code', $code)->where('user_id', $userId)->exists();
    }

    public function assignCodeToUser($code, $userId)
    {
        $code->is_taken = true;
        $code->user_id = $userId;
        $code->save();
    }

    public function findByActivationCode(string $activationCode){
        $code = Code::where('activation_code' , $activationCode);
        if(!$code){
            throw new ModelNotFoundException("There is no such code" , 404);
        }
        return $code;
    }

    public function create(array $data)
{
    return Code::create([
        'activation_code' => $data['activation_code'],  // Corrected typo here
        'is_taken' => false,
        'user_id' => null
    ]);
}


    public function update(Code $code, array $data)
    {
        return $code->update($data);
    }

    public function delete(Code $code)
    {
        return $code->delete();
    }
}
