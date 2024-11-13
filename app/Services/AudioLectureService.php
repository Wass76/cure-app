<?php

namespace App\Services;

use App\Repositories\AudioLectureRepository;

class AudioLectureService
{
    protected $audioLectureRepository;

    public function __construct(AudioLectureRepository $audioLectureRepository)
    {
        $this->audioLectureRepository = $audioLectureRepository;
    }

    public function getAllAudioLectures()
    {
        return $this->audioLectureRepository->getAll();
    }

    public function getAudioLectureById($id)
    {
        return $this->audioLectureRepository->findById($id);
    }

    public function createAudioLecture(array $data)
    {
        return $this->audioLectureRepository->create($data);
    }

    public function updateAudioLecture($id, array $data)
    {
        $audioLecture = $this->audioLectureRepository->findById($id);
        return $this->audioLectureRepository->update($audioLecture, $data);
    }

    public function deleteAudioLecture($id)
    {
        $audioLecture = $this->audioLectureRepository->findById($id);
        return $this->audioLectureRepository->delete($audioLecture);
    }
}
