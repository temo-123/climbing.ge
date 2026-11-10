@component('mail::message')
# Your comment was hidden

Hi,

we had to hide your comment on **{{ $comented_title }}**, because it didn't follow our community rules. Other visitors can't see it anymore.

@component('mail::panel')
**Reason:** {{ $cause }}<br>
**When:** {{ $data_time }}<br>
**Violations so far:** {{ $violation_count }} of 5
@endcomponent

We know it happens — just keep in mind that after **5 violations** the account and email get blocked. We'd much rather keep you around! 💛

Think this was a mistake? Reply to this email and tell us why.

The {{ config('app.name') }} team
@endcomponent
