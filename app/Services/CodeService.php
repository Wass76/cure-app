<?php

namespace App\Services;

use App\Repositories\CodeRepository;
use App\Exceptions\ModelNotFoundException;
use Exception;
use App\Models\Subject;
use Nette\Utils\Random;

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

        // Generate the specified number of codes
        for ($i = 0; $i < $data['number_of_codes']; $i++) {
            $activationCode = bin2hex(random_bytes(12));
            $code = $this->codeRepository->create(['activation_code' => $activationCode]);

            // Attach subjects to the new code
            $code->subjects()->attach($data['subjects']);
            $createdCodes[] = $code;
        }

        return $createdCodes;

    } catch (ModelNotFoundException $e) {
        throw new ModelNotFoundException("One or more specified subjects do not exist.");
    } catch (Exception $e) {
        throw new Exception("Failed to create codes.");
    }
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
