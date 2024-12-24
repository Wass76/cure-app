<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $subjects = $this->subjectService->getAllSubjects();
        return response()->json($subjects);
    }

    // public function getAllSubjectsByCode(){
    //      $code = 'ssss';
    //     return $this->subjectService->getAllSubjectsByCode($code);
    // }

    public function show($id)
    {
        $subject = $this->subjectService->getSubjectById($id);
        return response()->json($subject);
    }

    public function getSubjectsForUser(){
        return $this->subjectService->getSubjectsForUser();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:1,2',
        ], [
            'type.in' => 'The type field must be either 1 or 2.',
        ]);

        $subject = $this->subjectService->createSubject($data);
        return response()->json($subject, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:1,2',
        ]);

        $subject = $this->subjectService->updateSubject($id, $data);
        return response()->json($subject);
    }

    public function destroy($id)
    {
        $this->subjectService->deleteSubject($id);
        return response()->json(null, 204);
    }

    public function getUserCountBySubject()
    {            // Get the count of users per subject
            $userCounts = $this->subjectService->getUserCountBySubject();

            return response()->json([
                'message' => 'User count for each subject fetched successfully',
                'data' => $userCounts
            ], 200);
    }
}
