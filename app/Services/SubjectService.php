<?php

// app/Services/SubjectService.php
namespace App\Services;

use App\Repositories\CodeRepository;
use App\Repositories\LectureRepository;
use App\Repositories\SubjectRepository;
use App\Exceptions\ModelNotFoundException;
use App\Models\Code;
use Auth;
use Illuminate\Auth\AuthenticationException;

class SubjectService
{
    protected $subjectRepository;
    protected $codeRepository;
    protected $lectureRepository;

    public function __construct(
        SubjectRepository $subjectRepository ,
         CodeRepository $codeRepository ,
          LectureRepository $lectureRepository
          )
    {
        $this->subjectRepository = $subjectRepository;
        $this->codeRepository = $codeRepository;
        $this->lectureRepository = $lectureRepository;
    }

    public function getAllSubjects()
    {
        return $this->subjectRepository->getAll();
    }

    public function getSubjectById($id)
    {
        $subject = $this->subjectRepository->findById($id);
        if (!$subject) {
            throw new ModelNotFoundException("Subject with ID {$id} not found.");
        }
        return [
            'id' => $subject->id,
            'name' => $subject->name,
            'type' => $subject->type,
            'countOfLectures' => $this->lectureRepository->countLecturesBySubject($subject->id),
            'created_at' => $subject->created_at,
            'updated_at' => $subject->updated_at
        ];
    }

    // LectureService.php

    public function getSubjectsForUser()
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException("There is no authenticated user");
        }

        // Fetch the subjects associated with the user's codes without duplicates
        $subjects = $this->subjectRepository->getSubjectsByUserCodes($user->id);

        return response()->json([
            'subjects' => $subjects
        ]);
    }




    // public function getAllSubjectsByCode(string $activateCode){
    //     $code = $this->codeRepository->findByActivationCode($activateCode);

    //     $data = $this->subjectRepository->getAllSubjectsByCode(1);
    // }

    public function createSubject(array $data)
    {
        return $this->subjectRepository->create($data);
    }

    public function updateSubject($id, array $data)
    {
        $subject = $this->subjectRepository->findById($id);
        if (!$subject) {
            throw new ModelNotFoundException("Subject with ID {$id} not found.");
        }
        return $this->subjectRepository->update($subject, $data);
    }

    public function deleteSubject($id)
    {
        $subject = $this->subjectRepository->findById($id);
        if (!$subject) {
            throw new ModelNotFoundException("Subject with ID {$id} not found.");
        }
        return $this->subjectRepository->delete($subject);
    }


    public function getUserCountBySubject()
    {
        // Fetch the count of users per subject from the repository
        return $this->subjectRepository->countUsersBySubject();
    }
}
