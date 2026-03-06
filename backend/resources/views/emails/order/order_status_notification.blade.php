@component('mail::message')
# Order status while by updated

Your order whith id - {{ $order_id }}  while by updated, at - {{ $data_time }}.

Plees check order list for get more information

@component('mail::button', ['url' => $action_url])
Older list
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
