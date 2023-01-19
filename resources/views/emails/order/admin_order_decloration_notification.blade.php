@component('mail::message')
# New order

<h1>The user <strong>{{$name}} {{$surname}}</strong> (<strong>Whith id - {{ $user_id }}</strong>) has placed an order for the product/s, please check the order page</h1>

@component('mail::button', ['url' => $action_url])
Orders paga
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
