@component('mail::message')
# ENTRANCE EXAMINATION

<p>
Dear {{ ucwords($data->applicant->first_name) }},
</p>
<p>
Good day! <br>
Thank you for your payment.  We have received Php 300.00 as payment for the Entrance Examination.
Kindly login to the Applicant Portal and use this code to proceed to the entrance examination.
You may now proceed with the <b><i>Entrance Examination</i></b>  using this code: <b>{{ $exam_code }}</b>
</p>

<p>Kindly login to your Applicant Portal to Proceed to the Entrance Examination. </p>
@component('mail::button', ['url' => env('APP_STUDENT_URL')])
LOG IN NOW
@endcomponent
<p><b>Scheduled Examination:</b> Your entrance examination is scheduled for <b>[Date and Time]</b>.
Please make sure to log in and complete the examination at the specified time to ensure a smooth process.
</p>
<p>
<b>Code for Examination Access:</b> Use the following code to access your entrance examination: [Insert Code Here]. This code is unique to your account and should be kept confidential.
</p>
@include('widgets.mail.footer')
@endcomponent
