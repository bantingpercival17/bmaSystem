@component('mail::message')

Good Day,

The Concern Department responded to your concern.

<p>Ticket No: <b><i>{{$data->ticket_number}}</i></b></p>
@component('mail::button', ['url' =>'bma.edu.ph/bma/ticket/view?_t=' .base64_encode($data->ticket_number)])
Visit Here
@endcomponent

@include('widgets.mail.footer')
@endcomponent
