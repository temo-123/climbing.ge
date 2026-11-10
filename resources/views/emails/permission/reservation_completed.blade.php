@component('mail::message')
# Your access was updated 🔑

Hi,

{{ $message }}

Log in to your account to see what's new for you.

@component('mail::button', ['url' => env('APP_SSH', 'https://') . env('USER_PAGE_URL', 'user.climbing.ge')])
Open my account
@endcomponent

The {{ config('app.name') }} team
@endcomponent
