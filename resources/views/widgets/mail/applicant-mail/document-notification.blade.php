@component('mail::message')

Good day {{ ucwords($data->name) }},

<p>We want to inform you that we have not received your <b><i>{{ $document->document_name }}</i></b> yet. It's important
that you submit this document as soon as possible to prevent any delays in processing your application. </p>
<p>Click the login button below to continue with your application.</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
