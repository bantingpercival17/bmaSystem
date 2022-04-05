@component('mail::message')
# CONGRATULATIONS
Good day {{ucwords($data->account->applicant->first_name)}},
<p>
We are glad to inforn you that you are eligible to take the Online Entrance Examination for the 2022-2023 academic year.
You may now pay the Php 300.00 examination fee.
</p>

**Payment Method:** <br>
Kindly attach your proof of payment at your BMA ACCOUNT
Please note that you will only be able to take the Entrance Exam after payment verification.
Payment verification may take 2-3 banking days.
You may pay via bank deposit/online fund transfer by using the bank details below: <br><br>
**For Incoming Grade 11 Examinees:** <br>
BANK : **_LANDBANK OF THE PHILLIPINES_** <br>
ACCOUNT NAME : **_BALIWAG MARITIME FOUNDATION, INC._** <br>
ACCOUNT NUMBER : **_0102112822_** <br><br>
**For Incoming 1st year College Examinees**
BANK : **_Bank of Commerce_** <br>
ACCOUNT NAME : **_BALIWAG MARITIME ACADEMY, INC_** <br>
ACCOUNT NUMBER : **_062000001037_** <br><br>

@component('mail::button', ['url' => 'http://bma.edu.ph/bma/login'])
LOG IN NOW
@endcomponent
@include('widgets.mail.footer')
@endcomponent
