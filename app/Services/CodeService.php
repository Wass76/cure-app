<?php

namespace App\Services;

use App\Exceptions\UnauthorizedAccessException;
use App\Repositories\CodeRepository;
use App\Exceptions\ModelNotFoundException;
use Auth;
use Exception;
use App\Models\Subject;
use Illuminate\Auth\AuthenticationException;
use Request;

class CodeService
{
    protected $codeRepository;

    public function __construct(CodeRepository $codeRepository)
    {
        $this->codeRepository = $codeRepository;
    }

    public function getAllCodes()
    {
        return $this->codeRepository->getAll();
    }

    public function getCodeById($id)
    {
        $code = $this->codeRepository->findById($id);
        if (!$code) {
            throw new ModelNotFoundException("Code with ID {$id} not found.");
        }
        return $code;
    }

    public function createCode(array $data)
    {
        try {
            // Fetch the specified subjects
            $subjects = Subject::whereIn('id', $data['subjects'])->get();

            // Check if all subjects are found
            if ($subjects->count() !== count($data['subjects'])) {
                throw new ModelNotFoundException("One or more specified subjects do not exist.");
            }

            $createdCodes = [];
            // echo 1;

            for ($i = 0; $i < $data['number_of_codes']; $i++) {
                $activationCode = bin2hex(random_bytes(12));
                // echo $i;
                $code = $this->codeRepository->create(['activation_code' => $activationCode]);

                $code->subjects()->attach($data['subjects']);
                $createdCodes[] = $code;
            }

            return $createdCodes;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("One or more specified subjects do not exist.");
        } catch (Exception $e) {
            throw new Exception("Failed to create codes." . $e->getMessage()) ;
        }
    }

    public function addCodeToUser( $code){
        // echo $code;
        $user = Auth::user();
        if (!$user) {
            throw new AuthenticationException("There is no authenticated user");
        }
        try{
            if($user->role == 'admin'){
                throw new UnauthorizedAccessException("admin can't assign codes");
            }
            $objectCode = $this->codeRepository->findValidCode($code);
            $this->codeRepository->assignCodeToUser($objectCode, $user->id);

            return response()->json(
                [
                    'message' => 'Assign Code To User done successfully'
                ]
                );
        }
        catch(UnauthorizedAccessException $e){
            throw new UnauthorizedAccessException("admin can't assign codes");
        }
        catch (Exception $e) {
            throw new Exception("Failed Assign Code To User" . $e->getMessage(), 500);
        }
    }


    public function statistics(int $type){
        return response()->json([
            "number of users in block " . $type => $this->codeRepository->statistics($type)
        ]);
    }


    public function updateCode($id, array $data)
    {
        $code = $this->getCodeById($id); // Throws exception if not found
        return $this->codeRepository->update($code, $data);
    }

    public function deleteCode($id)
    {
        $code = $this->getCodeById($id); // Throws exception if not found
        return $this->codeRepository->delete($code);
    }
}
