@component('mail::message')
# Let's get you back in 🔑

Hi{{ !empty($name) ? ' ' . $name : '' }},

we got a request to reset the password of your climbing.ge account. Press the button below to choose a new one.

@component('mail::button', ['url' => $action_url])
Choose a new password
@endcomponent

The link works for {{ $expire }} minutes.

<p style="font-size:13px;color:#87939b;">Didn't ask for this? No problem — just ignore this email and your password stays the same.</p>

The {{ config('app.name') }} team
@endcomponent
