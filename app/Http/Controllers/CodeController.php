<?php

namespace App\Http\Controllers;

use App\Services\CodeService;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NotFoundException;

class CodeController extends Controller
{
    protected $codeService;

    public function __construct(CodeService $codeService)
    {
        $this->codeService = $codeService;
    }

    public function index()
    {
        $codes = $this->codeService->getAllCodes();
        return response()->json($codes);
    }

    public function show($id)
    {
        $code = $this->codeService->getCodeById($id);
        return response()->json($code);
    }


public function generateCodes(Request $request)
{
    try {
        $data = $request->validate([
            'number_of_codes' => 'required|integer|min:1',
            'subjects' => 'required|array',
            'subjects.*' => 'integer|exists:subjects,id'
        ]);

        $codes = $this->codeService->createCode($data);
        return response()->json($codes, 201);

    } catch (ValidationException $e) {
        // Return a 422 Unprocessable Entity response if validation fails
        return response()->json([
            'error' => 'Validation failed.',
            'details' => $e->render(),
        ], 422);

    } catch (ModelNotFoundException $e) {
        // Return a 404 Not Found if a subject is not found
        return response()->json([
            'error' => 'One or more specified subjects do not exist.'
        ], 404);

    }
}


    public function assignCodeToUser(Request $request){
        // echo $request->code;

        $code = $request->code;

        return $this->codeService->addCodeToUser($code);

    }


    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'active_code' => 'sometimes|required|string|unique:codes,active_code,' . $id,
        ]);

        $code = $this->codeService->updateCode($id, $data);
        return response()->json($code);
    }

    public function destroy($id)
    {
        $this->codeService->deleteCode($id);
        return response()->json(null, 204);
    }
}
