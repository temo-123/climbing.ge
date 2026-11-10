@component('mail::message')
# Welcome to climbing.ge! 🎉

We're really happy you're here.

{{ $text }}

## Here's what you can do

- 🧗 **Find routes** — crags, multi-pitches, ice and mountains all over Georgia
- ✅ **Log your ascents** and keep your climbing diary
- 🏔 **Book tours** with local guides
- 🛒 **Get gear** in our shop

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Go to my profile
@endcomponent

Questions or ideas? Just reply — we read every message.

See you on the rock! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
