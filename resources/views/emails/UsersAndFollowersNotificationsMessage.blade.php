@component('mail::message')
    # Message from - {{ $from_site }}

    <hr>

    <p>{{$msg}}</p>

    @component('mail::panel', ['url' => ''])
        <p>Email - {{$email}}</p>
    @endcomponent

    {{ config('app.name') }}

    <hr>

    <button>Unfollow</button>
@endcomponent
