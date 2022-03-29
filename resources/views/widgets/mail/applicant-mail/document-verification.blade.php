@component('mail::message')
Good day {{ucwords($data->account->applicant->first_name)}},

@if ($data->is_approved == 1)
<p>The Registrar's Office has checked and validated your <b>{{ ucwords(trim(str_replace('_', ' ', $data->document->document_name)))}}</b> has been <b>APPROVED</b></p>
@endif
@if ($data->is_approved == 2)
<p>The Registrar's Office has checked and validated your <b>{{ ucwords(trim(str_replace('_', ' ', $data->document->document_name)))}}</b> has been <b>DISAPPROVED</b></p>
<p><i>Feedback: </i> {{$data->feedback}}</p>
@endif
<p>Kindly visit to the your Applicant Portal to check your status. </p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
