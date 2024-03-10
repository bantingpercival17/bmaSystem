<?php

namespace App\Http\Livewire\Teacher\CourseSyllabus;

use App\Models\SyllabusCourseLearningOutcome;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SubjectTopicView extends Component
{
    public $subjectTopic;
    public $activeCard = 'topic-details';
    public function render()
    {
        $this->subjectTopic = request()->query('topic') ? SyllabusCourseLearningOutcome::find(base64_decode(request()->query('topic'))) : $this->subjectTopic;
        return view('livewire.teacher.course-syllabus.subject-topic-view');
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        Cache::put('menu', $data, 120);
    }
}
