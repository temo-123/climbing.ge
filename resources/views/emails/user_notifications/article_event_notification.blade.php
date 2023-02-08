@component('mail::message')
# Introduction

The body of your message.

locale - {{ $locale_article }}
global - {{ $global_article }}

@component('mail::button', ['url' => ''])
Article
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
