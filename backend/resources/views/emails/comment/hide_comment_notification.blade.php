@component('mail::message')
# Your coment is delited!

<p>We found a violation in your comment, to the - {{ $comented_title }}.</p>

<p>We removed your comment for the reason - "{{ $cause }}". Users will no longer see this comment</p>

<p>If the number of violations exceeds 5, your account and email will be blocked! (Number of violations - {{$violation_count}})</p>

<p>Deliting time - {{ $data_time }}</p>

<p>Please do not violate the terms of use of the service!</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
