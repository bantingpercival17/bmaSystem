@component('mail::message')
# Virtual Briefing
Good day {{ ucwords($data->name) }},
<p> Congratulations on Passing the Entrance Examination.</p>
<p>
    On May 25, 2022 (Wednesday), you will be having a Virtual Briefing regarding Medical Examination and Enrollment
    Guidelines. The Virtual Interview will be composed of Video Recordings, Computer Based Test, and a Brief Summary of
    Each topic.
</p>
@include('widgets.mail.footer')
@endcomponent
