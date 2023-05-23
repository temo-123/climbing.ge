@component('mail::message')
# Comment Complaint Decision

<p>We have considered your complaint and made a decision</p>

<p>{{ $decision_message }}</p>

<p>Visited our site more often!
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
