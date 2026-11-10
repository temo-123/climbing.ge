@component('mail::message')
# Please confirm your email ✉️

Hi{{ !empty($name) ? ' ' . $name : '' }},

thanks for signing up for climbing.ge! One last thing: please confirm that this is your email address.

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Confirm my email
@endcomponent

<p style="font-size:13px;color:#87939b;">Didn't create an account? Then you can safely ignore this email.</p>

See you in the mountains! 🏔<br>
The {{ config('app.name') }} team
@endcomponent
