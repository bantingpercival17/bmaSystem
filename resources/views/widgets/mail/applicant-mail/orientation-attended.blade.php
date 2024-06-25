@component('mail::message')
# MEDICAL SCHEDULE

Good day {{ ucwords($data->name) }},
<p>Thank you for attending our In-person Briefing / Orientation. It was great seeing you there; we hope you found it informative. As the next step, you may select an appointment for your Medical Examination Schedule. This is an important part of the process, and we encourage you to schedule it as soon as possible to ensure that everything proceeds smoothly.</p>
<p>If you have any questions or need assistance with scheduling, please don't hesitate to contact us on this hotline (044) 766 1263.”</p>
<p>Kindly login to your Applicant Portal to Proceed to the <b>MEDICAL APPOINTMENT</b>. </p>
@component('mail::button', ['url' =>'http://bma.edu.ph/#/applicant/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
