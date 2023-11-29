@component('mail::message')
# RESET YOUR PASSWORD?
<p>If you requested a password reset for {{ $data->email }}, use the password code below to complete the process.
If you didn't make this request, ignore this email.</p>
<p>
PASSWORD: <b> <i>{{ $password }}</i></b>
</p>
<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => 'http://student.bma.edu.ph/student/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.student-mail.footer')
@endcomponent
