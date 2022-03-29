@component('mail::message')
Good day,

<p>Thank for choosing Baliwag Maritime Academy, Inc to reach your Maritime Dream. </p>

<p>I like to inform to that you can now attach your required document to the Applicant Portal.</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
