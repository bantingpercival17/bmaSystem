@component('mail::message')
Good day {{ucwords($data->account->applicant->first_name)}},

@if ($data->is_approved == 1)
<p>The Registrar's Office has checked and validated your <b>{{ ucwords(trim(str_replace('_', ' ', $data->document->document_name)))}}</b> has been <b>APPROVED</b></p>
@endif
@if ($data->is_approved == 2)
<p>
Upon checking and verification of our Registrar’s Office your <b>{{ ucwords(trim(str_replace('_', ' ', $data->document->document_name)))}}</b> has been disapproved due to : <b><i>{{$data->feedback}}</i></b>.
<br>
Kindly re-submit your clear and correct <b>{{ ucwords(trim(str_replace('_', ' ', $data->document->document_name)))}}</b>.
</p>
@endif
<p>Kindly visit to the your Applicant Portal to check your status. </p>
@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
