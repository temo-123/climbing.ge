@component('mail::message')
# Complaint about the comment

<p>One or more comments have been reported!</p>
<p>Plees check admin page for take more information!</p>

@component('mail::button', ['url' => $admin_panel_url])
Admin paga
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
