@component('mail::message')
<!-- # Introduction -->

<h1>Welcome in climbing.ge</h1>
<h2>Your Name - {{$user->name}}</h2>
<h2>Your Surname - {{$user->surname}}</h2>
<h2>Your Email - {{$user->email}}</h2>

Thanks, for your registration.
{{ config('app.name') }}

@component('mail::button', ['url' => 'http://climbing.ge/admin/home'])
Your profile.
@endcomponent

@endcomponent
