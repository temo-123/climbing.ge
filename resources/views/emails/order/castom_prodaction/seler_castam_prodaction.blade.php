@component('mail::message')
# Castom prodaction message!

{{-- <h1>Castom prodaction message</h1> --}}

<p>{{ $message }}</p>

{{-- @component('mail::panel', ['url' => '']) --}}
@component('mail::panel',)
    <h2>Buyer information</h2>
    <p>Buyer name - {{$messaged_user_name}}</p>
    <p>Buyer Email - {{$messaged_user_email}}</p>
    <p>Buyer Number - {{$buyer_number}}</p>
    <p>Product name (ID) - {{$product_name}} (#{{$product_id}})</p>
    <hr>
    <h2>Buyer message</h2>
    <p>{{ $buyer_message }}</p>
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
