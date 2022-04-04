@component('mail::message')
Welcome Aboard {{ ucwords($data->name) }},

<p>We have received your pre-registration as an incoming  {{ $data->course_id == 3 ? 'Grade 11' : '4th Class' }} for the {{ $data->course->course_name }} course for
Academic Year 2022 – 2023.</p>
<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
