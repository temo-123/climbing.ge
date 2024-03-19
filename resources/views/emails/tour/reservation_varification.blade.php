@component('mail::message')
{{-- # Your coment is delited! --}}

<p>We have received your reservation</p>

<p>Please go to the page and confirm your reservation.</p>

<p>If you do not complete the verification, the reservation will not be considered complete and we will not process it!</p>

@component('mail::button', ['url' => $action_url])
Confirm
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
