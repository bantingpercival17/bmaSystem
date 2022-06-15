@component('mail::message')
# Medical Examination
Good day {{ ucwords($data->name) }},
<p>
We are glad to inform you that you have passed the Medical Examination and are fit to enroll at Baliwag Maritime Academy.
</p>
<p>Click here to Enroll</p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
