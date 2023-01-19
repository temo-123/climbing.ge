@component('mail::message')
# Your coment is delited!

<p>We found a violation in your comment, to the article - {{ $article_title }}.</p>

<p>We removed your comment for the reason - "{{ $cause }}". Users will no longer see this comment</p>

<p>Deliting time - {{ $data_time }}</p>

<p>Please do not violate the terms of use of the service!</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
