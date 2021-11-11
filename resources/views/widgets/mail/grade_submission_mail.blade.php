@component('mail::message')
<p>
Your Faculty <b>{{ $_content->staff->user->name }}</b> submitted <b>{{ $_content->grade_submission->period }}</b> Grade
for <b>{{ $_content->section->section_name }} / {{ $_content->curriculum_subject->subject->subject_name }} </b> for your
approval.
</p>

<label for="">Faculty Name : </label>
<label for=""><b>{{ $_content->staff->first_name. " ".$_content->staff->last_name }}    </b></label> 

<label for="">Subject: </label>
<label for=""><b>{{ $_content->curriculum_subject->subject->subject_name }}</b></label> 

<label for="">Section: </label>
<label for=""><b> {{ $_content->section->section_name }} </b></label> 

<label for="">Term: </label>
<label for=""><b> {{ strtoupper($_content->grade_submission->period) }} </b></label> 

<label for="">Timestamp: </label>
<label for=""><b> {{ $_content->grade_submission->created_at->format('M d, Y') }} </b></label> 

<small>
Thanks,<br>
{{ config('app.name') }}
</small>

@endcomponent
