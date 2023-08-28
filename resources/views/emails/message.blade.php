@component('mail::message')
    # Message from - {{ $from_site }}

    <hr>

    <p>{{$msg}}</p>

    {{-- @component('mail::button', ['url' => ''])
    Button Text
    @endcomponent --}}

    @component('mail::panel', ['url' => ''])
        <p>From - {{$name}} {{$surname}}</p>
        <p>Email - {{$email}}</p>
        <p>Cuntry - {{$country}}</p>
        <p>Phon number - {{$num}}</p>
    @endcomponent

    {{-- Thanks,<br> --}}
    {{ config('app.name') }}
@endcomponent
