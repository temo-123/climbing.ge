@component('mail::message')
    # Message from - {{ $from_site }}

    {{$msg}}

    From - {{$name}} {{$surname}}
    Email - {{$email}}
    Cuntry - {{$country}}
    Phon number - {{$num}}

@endcomponent
