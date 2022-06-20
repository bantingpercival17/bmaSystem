@component('mail::message')
# Medical Examination
Hello {{ ucwords($data->name) }},
<p>
Thank you for your interest in studying at Baliwag Maritime Academy. We regret to inform you that you have not passed the Medical Examination and are unfit to enroll.
</p>
{{$data->medical_result}}
@endcomponent
@include('widgets.mail.footer')
@endcomponent
