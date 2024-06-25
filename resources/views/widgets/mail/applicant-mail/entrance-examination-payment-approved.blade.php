@component('mail::message')
# ENTRANCE EXAMINATION

<p>
Dear {{ ucwords($data->applicant->first_name) }},
</p>
<p>
Good day! <br>
Thank you for your payment. We have received Php 300.00 as payment for the Entrance Examination.
</p>
<p>Kindly login to your Applicant Portal to Proceed to the Entrance Examination. </p>
@component('mail::button', ['url' =>'http://bma.edu.ph/#/applicant/login'])
LOG IN NOW
@endcomponent
@php
$date = strtotime($examinationDetails->examination_scheduled->schedule_date);
$date = date("F j, Y \a\\t g:i A", $date);
@endphp
<p><b>Scheduled Examination:</b> Your entrance examination is scheduled on <b>{{ $date }}</b>.
Please ensure that you take the entrance examination on the specified date and time; otherwise, your examination
slot will be forfeited.
</p>
<p>
<b>Examination Access:</b> Use the following code to access your entrance examination:
<b>{{ $examinationDetails->examination_code }}</b>. This code is unique to your account and should be kept
confidential.
</p>
@include('widgets.mail.footer')
@endcomponent
