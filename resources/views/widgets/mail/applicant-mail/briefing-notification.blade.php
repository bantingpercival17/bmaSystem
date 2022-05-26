@component('mail::message')
# Virtual Briefing
Good day {{ ucwords($data->name) }},
<p>We sincerely apologize for the problems you have experienced being unable to access our online platform
    for our briefing/orientation. We are currently experiencing some technical issues with our process and we
    are working on this to get resolved. Again, sorry for the inconvenience and we will get back to you</p>

<p>Thank you for your understanding</p>
{{-- <p> Congratulations on Passing the Entrance Examination.</p>
<p>
    On May 25, 2022 (Wednesday), you will be having a Virtual Briefing regarding Medical Examination and Enrollment
    Guidelines. The Virtual Interview will be composed of Video Recordings, Computer Based Test, and a Brief Summary of
    Each topic.
</p> --}}
@include('widgets.mail.footer')
@endcomponent
