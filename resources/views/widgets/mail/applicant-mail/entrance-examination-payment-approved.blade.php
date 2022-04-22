@component('mail::message')
# ENTRANCE EXAMINATION

<p>
Good day {{ ucwords($data->applicant->first_name) }},
</p>
<p>
    Your payment has been approved.
    You may now proceed with the Entrance Examination using this code:<h2><b>{{ $exam_code }}</b></h2>
</p>

<p>Kindly login to your Applicant Portal to Proceed to the Entrance Examination. </p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
