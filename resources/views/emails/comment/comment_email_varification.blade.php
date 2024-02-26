@component('mail::message')
# Your coment is delited!

<p>Please go to the page and confirm your email.</p>

<p>Your comment will not be visible to other users until you confirm it!</p>

{{-- @component('mail::panel', ['url' => $url])
    <p>Confirm email</p>
@endcomponent --}}

@component('mail::button', ['url' => $action_url])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
