@component('mail::message')
Good day {{ ucwords($data->name) }},

<p>Thank you for completing your registration. This email is being sent to confirm that you are registered as an
incoming {{ $data->course_id == 3 ? 'Grade 11' : '4th Class' }} for the {{ $data->course->course_name }} course
Academic Year 2022 – 2023.</p>
<p>You may now proceed with the submission of the required documents (Step 2).</p>
<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
