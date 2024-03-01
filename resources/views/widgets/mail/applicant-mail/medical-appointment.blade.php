@component('mail::message')
# Medical Examination
Good day {{ ucwords($data->name) }},
<p>
Please be advised to report to Baliwag Maritime Academy, Inc. on {{$data->medical_appointment->appointment_date}}, at 10:00 AM for your medical examination schedule.
</p>
<p>
If you have any questions or concerns regarding the schedule, kindly contact Mr. Jennoe Repomanta at 09269558541 or email him at repomantajennoe61@gmail.com. for further assistance.
</p>
{{-- <p>Please report to Baliwag Maritime Academy on Friday between 09:00 am to 04:00 pm and look for Medical Officer Robert
Evangelista for your Physical Assessment.
</p> --}}
@include('widgets.mail.footer')
@endcomponent