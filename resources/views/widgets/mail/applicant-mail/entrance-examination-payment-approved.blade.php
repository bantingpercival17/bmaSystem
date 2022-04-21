@component('mail::message')
# ENTRANCE EXAMINATION

<p>
Good day {{ ucwords($data->applicant->first_name) }},
Your payment was verified to the Accounting's Office, You can now Take the Entrance Examination.
</p>
<p>
<small><b>EXAMINATION CODE:</b></small>
<h2><b>{{ $exam_code }}</b></h2>
</p>

<p>Kindly login to your Applicant Portal to Proceed to the Entrance Examination. </p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
