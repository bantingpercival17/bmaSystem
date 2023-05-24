@component('mail::message')
Hello {{ ucwords($data->name) }},
<p>We want to inform you that we have not received your [Missing Document] yet. It's important that you submit this document as soon as possible to prevent any delays in processing your application. </p>

<p>Thank you.</p>

<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
