@component('mail::message')
# New order 🛒

**{{ $name }} {{ $surname }}** (user #{{ $user_id }}) just placed an order. Let's get it packed and on its way!

@component('mail::button', ['url' => $action_url, 'color' => 'success'])
Open orders
@endcomponent

Have a good day! ☀️<br>
The {{ config('app.name') }} team
@endcomponent
