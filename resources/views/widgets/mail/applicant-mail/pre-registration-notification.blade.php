@component('mail::message')
Welcome Aboard {{ ucwords($data->name) }},

<p>We have received your pre-registration as an incoming {{ $data->course_id == 3 ? 'Grade 11' : '4th Class' }} for the
{{ $data->course->course_name }} course for
Academic Year {{ $semester->school_year }}.</p>
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
