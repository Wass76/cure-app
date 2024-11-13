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

    public function findByActivationCode(string $activationCode){
        $code = Code::where('activation_code' , $activationCode);
        if(!$code){
            throw new ModelNotFoundException("There is no such code" , 404);
        }
        return $code;
    }

    public function create(array $data)
    {
        return Code::create($data);
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
