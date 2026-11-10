@component('mail::message')
# {{ $title }} 📋

Hi,

{{ $text }}

@component('mail::button', ['url' => $action_url])
Open the task panel
@endcomponent

Thanks for your help! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
