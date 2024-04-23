@component('mail::message')
<!-- # BRIEFING / ORIENTATION PROGRAM

Good day{{ ucwords($data->name) }},
<p>Congratulations on passing the entrance examination of Baliwag Maritime Academy, Inc. for AY
2023-2024.
For more information regarding the policy of the Academy and Enrollment Process and Procedure, a <b>BRIEFING/
ORIENTATION</b> is scheduled on
<b>{{ $data->schedule_orientation->schedule_date }}</b> at
<b> {{ $data->schedule_orientation->schedule_time }}</b> at at Baliwag Maritime Academy, Inc. 3rd Floor Audio Visual
Room.
</p>
<p>We highly encourage your Parents/Guardians to attend the Briefing/Orientation. Please wear appropriate attire (sando,
shorts, and slippers are not allowed)</p> -->
# Medical Examination
Good day {{ ucwords($data->name) }},
<p>
Please be advised to report to Baliwag Maritime Academy, Inc. on {{$data->schedule_orientation->schedule_date}}, at 10:00 AM for your medical examination schedule.
</p>
<p>
If you have any questions or concerns regarding the schedule, kindly contact Mr. Jennoe Repomanta at 09269558541 or email him at repomantajennoe61@gmail.com. for further assistance.
</p>

@include('widgets.mail.footer')
@endcomponent
