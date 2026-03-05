@component('mail::message')
# Complaint about the comment

<p>To your comment for the article - “{{$article_title}}” is the answer. Check the article to see it</p>

@component('mail::button', ['url' => $url])
Check article page
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
