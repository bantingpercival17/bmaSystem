@component('mail::message')
# Briefing Program
Good day {{ ucwords($data->name) }},
<p>
Congratulations on passing the entrance examination of Baliwag Maritime Academy, Inc. for AY 2023-2024. For more information regarding the policy of the Academy and enrollment process and procedure, a BRIEFING/ORIENTATION is scheduled on May 2, 2023, at 2:00 pm at Baliwag Maritime Academy, Inc. 3rd Flr. Audio Visual Room.
We highly encouraged your parents/guardians to attend the said briefing/orientation. Please wear appropriate attire (sando, shorts, and slippers are not allowed).
Thank you.
</p>
@include('widgets.mail.footer')
@endcomponent
