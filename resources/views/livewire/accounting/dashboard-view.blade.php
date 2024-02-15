@php
$pageTitle = "Dashboard";
@endphp
@section('page-title', $pageTitle)
<div>
    <livewire:summary-components.enrollment-summary-overview/>
    <livewire:summary-components.applicant-summary-overview />
</div>