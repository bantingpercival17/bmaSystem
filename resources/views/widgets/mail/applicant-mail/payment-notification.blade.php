@component('mail::message')
# Examination Payment
Good day {{ ucwords($data->name) }},
<p>We are pleased to inform you that you have been approved to proceed to the next step in the examination process for the Entrance Examination. We kindly request that you complete the examination fee payment at your earliest convenience to confirm your interest in your application</p>
<p>
Payment Details:
<br>
Amount: 300 pesos
</p>
<p>
Please proceed to our payment portal at bma.edu.ph to complete the payment process. Ensure that you have your application reference number handy for a smooth transaction.
<br>
Thank you for your prompt attention to this matter.
</p>
@include('widgets.mail.footer')
@endcomponent