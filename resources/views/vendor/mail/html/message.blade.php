<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="env('APP_SSH', 'https://') . env('SITE_URL', 'climbing.ge')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
You're getting this email because of your activity on climbing.ge. Questions? Just reply — a real person reads every message.
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
