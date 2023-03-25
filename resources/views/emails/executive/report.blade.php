@component('mail::message')
# Attendance Monitoring of Midshipman
Date: {{ date('F d, Y') }}

Good day,
This is Automail for Onboarding & Libery Report in addition of List of Absent Midshipman

@include('widgets.mail.footer')
@endcomponent