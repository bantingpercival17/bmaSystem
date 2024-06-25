@component('mail::message')
# Medical Examination
Good day {{ ucwords($data->name) }},
@if ($data->medical_result->is_fit)
@if ($data->medical_result->is_fit == 1)
<p>
We are glad to inform you that you have passed the Medical Examination and are fit to enroll at Baliwag
Maritime
Academy.
</p>
<p>Click here to Enroll</p>
@component('mail::button', ['url' =>'http://bma.edu.ph/#/applicant/login'])
LOG IN NOW
@endcomponent
@elseif($data->medical_result->is_fit === null)
<p>
MEDICAL EXAMINATION RESULT : <b>PENDING</b> <br>
REASON: {{ $data->medical_result->remarks }}
</p>
@else
<p>
MEDICAL EXAMINATION RESULT : <b>FAILED</b> <br>
REASON: {{ $data->medical_result->remarks }}
</p>
<p>
Thank you for your interest in studying at Baliwag Maritime Academy. We regret to inform you that you have
not
passed the Medical Examination and are unfit to enroll.
</p>
@endif
@else($data->medical_result->is_fit === null)
<p>
MEDICAL EXAMINATION RESULT : <b>PENDING</b> <br>
REASON: {{ $data->medical_result->remarks }}
</p>
@endif

@include('widgets.mail.footer')
@endcomponent
