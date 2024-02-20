<?php

namespace App\Http\Livewire\Registrar\Subjects\SubjectHandle;

use App\Exports\WorkBook\TeachingLoadAndScheduleWorkBook;
use App\Imports\SubjectScheduleImport;
use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\EnrollmentAssessment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Livewire\WithFileUploads;

class SubjectHandleView extends Component
{
    use WithFileUploads;
    public $academic;
    public $course;
    public $selectedCourse = null;
    public $selectedCurriculum = null;
    public $selectCourse = null;
    public $selectCurriculum = null;
    public $fileImport;
    public function render()
    {
        $courseLists = CourseOffer::all();
        $curriculumLists = Curriculum::where('is_removed', false)->orderBy('id', 'desc')->get();
        $this->academic = $this->setAcademicYear();
        $academicDetails = AcademicYear::find(base64_decode($this->academic));
        $this->course = $this->setCourse();
        $curriculum = $this->setCurriculum();
        $subjectLists  = $this->viewData($this->course, $academicDetails, $curriculum);
        /* echo dd($subjectData); */
        return view('livewire.registrar.subjects.subject-handle.subject-handle-view', compact('courseLists', 'academicDetails', 'curriculumLists',  'subjectLists', 'curriculum'));
    }
    function viewData($course, $academic, $curriculum)
    {
        $subjectLists = [];
        $levels = [11, 12];
        $levels = $course->id != 3 ? [4, 3, 2, 1] : $levels;
        $subjectLists = [];
        foreach ($levels as $key => $value) {
            $subjectData = CurriculumSubject::with(['subject', 'sectionList' => function ($query) {
                $query->where('academic_id', base64_decode($this->academic));
            }])
                ->where('curriculum_subjects.course_id', $course->id)
                ->where('curriculum_subjects.curriculum_id', $curriculum->id)
                ->where('curriculum_subjects.year_level', $value)
                ->where('curriculum_subjects.semester', $academic->semester)
                ->where('curriculum_subjects.is_removed', false)
                ->orderBy('curriculum_subjects.id', 'asc')->get();
            $subjectLists[] = array(
                'year_level' => strtoupper(Auth::user()->staff->convert_year_level($value)),
                'subject_lists' => $subjectData
            );
        }
        return $subjectLists;
    }
    function setAcademicYear()
    {
        $data = $this->academic;
        if ($this->academic == '') {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        }
        if (request()->query('_academic')) {
            $data = request()->query('_academic') ?: $this->academic;
        }
        return $data;
    }
    function setCourse()
    {
        $course = CourseOffer::find(1);
        if ($this->selectCourse !== null) {
            $course = CourseOffer::find($this->selectCourse);
        }
        $this->selectedCourse = strtoupper($course->course_name);
        return $course;
    }
    function setCurriculum()
    {
        $data = Curriculum::where('is_removed', false)
            ->orderBy('id', 'desc')
            ->first();
        if ($this->selectCurriculum) {
            $data = Curriculum::find($this->selectCurriculum);
        }
        $this->selectedCurriculum = strtoupper($data->curriculum_name);
        return $data;
    }
    function exportTeachingLoadTemplate()
    {
        $data = EnrollmentAssessment::select('course_id', 'year_level', 'curriculum_id', 'academic_id')
            ->with(['course', 'curriculum'])
            ->where('academic_id', base64_decode($this->academic))
            ->groupBy(['course_id', 'year_level', 'curriculum_id'])->get();
        try {
            $academic = AcademicYear::find(base64_decode($this->academic));
            $current_academic =  strtoupper(str_replace(' ', '-', $academic->semester)) . '-' . $academic->school_year;
            $_file_name = 'storage/department/registrar/zip-file/' . $current_academic . '-TEACHING-LOAD-TEMPLATES-' . date('Ymdhms');
            // Create a new zip archive
            $zipFileName = $_file_name . '.zip';
            $zip = new ZipArchive();
            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                foreach ($data as $key => $value) {
                    #FILENAME FORMAT: COURSE-YEARLEVEL-CURRICULUM
                    $fileName = $value->course->course_code . '-' . Auth::user()->staff->convert_year_level($value->year_level) . '-' . $value->curriculum->curriculum_name;
                    $fileName = strtoupper($fileName);
                    #FILE EXPORT MODEL
                    $fileExport = new TeachingLoadAndScheduleWorkBook($value);
                    #DONWLOAD THE FILES
                    $fileContents = Excel::download($fileExport, $fileName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX)->getFile();
                    #ADD TO THE ZIP FOLDER
                    $zip->addFromString($fileName . '.xlsx', file_get_contents($fileContents)); // Add the file to the zip archive
                }
                $zip->close();
                return redirect(asset($zipFileName));
                //unlink($zipFileName);
            } else {
                echo "Failed to create the zip archive.";
            }
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'System Bug!',
                'text' => $th->getMessage(),
                'type' => 'Warning',
            ]);
        }
    }
    function importTeachingLoad()
    {

        $this->validate([
            'fileImport' => 'required',
        ]);
        try {
            $this->dispatchBrowserEvent('show-loading');
            $fileName = $this->fileImport->getClientOriginalName();
            $fileName = "/registrar/scheduled-import/" . $fileName;
            $this->fileImport->store($fileName, 'public');
            Excel::import(new SubjectScheduleImport(), $this->fileImport);
            $this->dispatchBrowserEvent('hide-loading');
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Success!',
                'text' => "Uploading Complete",
                'type' => 'success',
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('hide-loading');
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'System Bug!',
                'text' => $th->getMessage(),
                'type' => 'warning',
            ]);
        }
    }
}
