<?php

namespace App\Http\Controllers;

use App\Services\PdfLectureService;
use Illuminate\Http\Request;

class PdfLectureController extends Controller
{
    protected $pdfLectureService;

    public function __construct(PdfLectureService $pdfLectureService)
    {
        $this->pdfLectureService = $pdfLectureService;
    }

    public function index()
    {
        $pdfLectures = $this->pdfLectureService->getAllPdfLectures();
        return response()->json($pdfLectures);
    }

    public function show($id)
    {
        $pdfLecture = $this->pdfLectureService->getPdfLectureById($id);
        return response()->json($pdfLecture);
    }

    public function downloadPDF($id){
        return $this->pdfLectureService->downloadPdfLecture($id);
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'lecture_id' => 'required|exists:lectures,id',
    //         'file_name' => 'required|string',
    //         'file_size' => 'required|integer',
    //     ]);

    //     $pdfLecture = $this->pdfLectureService->createPdfLecture($data);
    //     return response()->json($pdfLecture, 201);
    // }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'file_name' => 'sometimes|required|string',
            'file_size' => 'sometimes|required|integer',
        ]);

        $pdfLecture = $this->pdfLectureService->updatePdfLecture($id, $data);
        return response()->json($pdfLecture);
    }

    public function destroy($id)
    {
        $this->pdfLectureService->deletePdfLecture($id);
        return response()->json(null, 204);
    }
}
