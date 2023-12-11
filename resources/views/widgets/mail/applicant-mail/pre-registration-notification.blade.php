@component('mail::message')
Welcome Aboard {{ ucwords($data->name) }},
<p>
We are pleased to inform you that we have received your registration as an incoming
<b>{{ $data->course_id == 3 ? 'Grade 11 Student' : '4th Class Midshipman' }}</b> for the
<b>{{ $data->course->course_name }}</b>
course for the A.Y. <b>{{ $semester->school_year }}</b>.
</p>
<p>To proceed with your application, please use the following login credentials:</p>
<p>
<b>EMAIL:</b> <i>{{ $data->email }}</i> <br>
<b>PASSWORD:</b> <i>{{ $data->applicant_number }}</i>
</p>
<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => env('APP_STUDENT_URL')])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
