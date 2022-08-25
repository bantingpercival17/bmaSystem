@component('mail::message')
# WELCOME ABOARD!

<p>Good Day <b>{{ $data->first_name }}</b></p> <br>
<p>
Congratulations! You have completed your enrollment in Baliwag Maritime Academy, Inc. This email is to officially
welcome you as part of the BMA Family. As part of our family, we would like to give you the initial information you
need to start your journey as a BMA Cadet.
</p>
<p>Your Enrollment Information are as follows:</p>
<p>Full Name : <b>{{ $data->last_name . ', ' . $data->first_name }}</b><br>
Student Number : <b>{{ $data->account ? $data->account->student_number : '-' }}</b>
<br>
Course : <b>{{ $data->enrollment_assessment->course->course_name }}</b>
<br>
Year Level : <b>{{ Auth::user()->staff->convert_year_level($data->enrollment_assessment->year_level) }}</b>
<br>
@php
$_section = $data->section($data->enrollment_assessment->academic_id)->first();
@endphp
Section : <b>
{{ $_section ? $_section->section_name : 'No Section' }}</b>
</p>
<p><b>SUBJECT ENROLLED</b></p>
<table class="subject-list-table">
<thead>
<tr>
<th>SUBJECT CODE</th>
<th>DESCRIPTIVE TITLE</th>
<th>UNIT</th>
</tr>
</thead>
@php
$_enrollment_assessment = $data->enrollment_assessment;
@endphp
<tbody>
@if (count($_enrollment_assessment->course_subjects($_enrollment_assessment)))
@foreach ($_enrollment_assessment->course_subjects($_enrollment_assessment) as $_data)
@if ($_enrollment_assessment->bridging_program == 'with' || $_data->subject->subject_code != 'BRDGE')
<tr>
<td>{{ $_data->subject->subject_code }}</td>
<td>{{ $_data->subject->subject_name }}</td>

<td style="text-align: center">{{ $_data->subject->units }}</td>


</tr>
@endif
@endforeach
@else
<tr>
<td colspan="">No Subjects Encoded
</td>
</tr>
@endif

</tbody>
</table>
<br>
<p>You can also now login with your Account by using the following credentials:</p>
<p>
<h3><b>Seaversity Learning Management System (LMS) </b></h3>
<h4>Url: <a href="https://bma.seaversity.com.ph/login/index.php ">https://bma.seaversity.com.ph/login/index.php </a></h4>
<h4>Username: <b>-</b></h4>
<h4>Password: <b>-</b></h4>
</p>
<p>
Please note that the passwords given are all temporary password and you are required to change password immediately
upon login.
</p>
<p>
For issues and concerns regarding your account, you can send an email to ict@bma.edu.ph or file a ticket at
http://bma.edu.ph/bma/contact-us
</p>
<p>
Thank you for being part of our ever-growing family. Again, Welcome Aboard to BMA!
</p>
@include('widgets.mail.footer')
@endcomponent
