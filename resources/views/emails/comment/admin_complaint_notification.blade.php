@component('mail::message')
# A comment was reported 🚩

Hi team,

someone flagged one or more comments as breaking the rules. Could you take a look and decide whether to keep or hide them?

@component('mail::button', ['url' => $admin_panel_url])
Review the reports
@endcomponent

Thanks for keeping climbing.ge friendly! 🙌<br>
The {{ config('app.name') }} team
@endcomponent
