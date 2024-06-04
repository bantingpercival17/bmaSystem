@component('mail::message')

Dear {{ ucwords($data->name) }},

<p>
We hope this message finds you well. We are reaching out to remind you about the pending requirements for your application to Baliwag Maritime Academy. Completing these requirements is essential for you to proceed with the entrance examination.
</p>
<p>
Please submit the documents at your earliest convenience to avoid any delays in processing your application noncompliance would mean not pursuing your application. You can submit the required documents through your BMA Applicant Portal.
</p>
<p>
If you have any questions or need assistance, please do not hesitate to contact us at 0966 604 5925 or message us on our official Facebook page. We are here to help you through the process.
</p>
<p>
Thank you for your prompt attention to this matter. We look forward to receiving your completed application.
</p>
@component('mail::button', ['url' => env('APP_STUDENT_URL')])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
