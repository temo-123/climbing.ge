@component('mail::message')
# Welcome to climbing.ge! 🎉

@if($name)
Hi {{ $name }},
@endif

We're really happy you're here. Your account is ready — it's your home base for everything climbing in Georgia.

## Three things to start with

1. **Finish your profile** — add a photo and your grade, so other climbers know who you are.
2. **Find your next crag** — routes, topos and approach info for crags all over Georgia: [browse outdoor areas]({{ $outdoor_url }}).
3. **Log your first ascent** — tick routes and summits to earn points and keep your climbing diary. See who's most active on the [climbers list]({{ $climbers_url }}).

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Finish my profile
@endcomponent

Questions or ideas? Just reply — we read every message.

See you on the rock! 🧗<br>
The {{ config('app.name') }} team
@endcomponent
