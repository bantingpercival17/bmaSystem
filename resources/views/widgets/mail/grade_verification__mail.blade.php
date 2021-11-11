@component('mail::message')
@if ($_content->grade_submission->is_approved)
<p>
Your Department Head <b>{{ Auth::user()->name }}</b> approved your submitted grade for <b>{{ strtoupper($_content->grade_submission->period) }}</b> Grade
for <b>{{ $_content->curriculum_subject->subject->subject_name }} /{{ $_content->section->section_name }}</b>.
</p>
@else
Your Department Head <b>{{ Auth::user()->name }}</b> rejected your submitted grade for <b>{{ strtoupper($_content->grade_submission->period) }}</b> Grade
for <b>{{ $_content->curriculum_subject->subject->subject_name }} /{{ $_content->section->section_name }}</b>.

<label for="">Reason for Rejection : </label>
<label for=""> <b> {{ ucwords($_content->grade_submission->comments) }} </b> </label>
@endif


<small>
Thanks,<br>
{{ config('app.name') }}
</small>
@endcomponent
