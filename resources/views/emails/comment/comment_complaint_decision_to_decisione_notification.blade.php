@component('mail::message')
# We looked at your report ✅

Thanks for letting us know about that comment — reports like yours help keep climbing.ge a good place for everyone.

Here's what we decided:

@component('mail::panel')
{{ $decision_message }}
@endcomponent

If anything is still not right, just reply to this email.

Thanks for looking out for the community! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
