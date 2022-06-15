@component('mail::message')
# Medical Examination
Good day {{ ucwords($data->name) }},
<p>
This email is to confirm your appointment for Medical Examination at Central Port scheduled on <b>{{$data->medical_appointment->appointment_date}}</b>.
</p>

<p>Please report to Baliwag Maritime Academy on Friday between 09:00 am to 04:00 pm and look for Medical Officer Robert Evangelista for your Physical Assessment.
</p>
@include('widgets.mail.footer')
@endcomponent
