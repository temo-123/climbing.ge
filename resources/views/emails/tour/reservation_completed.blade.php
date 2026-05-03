@component('mail::message')

<p>{{ $message }}</p>

@if(isset($reservation) && $reservation)
<table style="width:100%;border-collapse:collapse;margin-top:15px;">
    @if(!empty($reservation['name']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;width:130px;">Name:</td>
        <td style="padding:6px 10px;">{{ $reservation['name'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['email']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Email:</td>
        <td style="padding:6px 10px;">{{ $reservation['email'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['phone']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Phone:</td>
        <td style="padding:6px 10px;">{{ $reservation['phone'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['check_in']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Check In:</td>
        <td style="padding:6px 10px;">{{ $reservation['check_in'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['persons']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Persons:</td>
        <td style="padding:6px 10px;">{{ $reservation['persons'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['country']) || !empty($reservation['city']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Location:</td>
        <td style="padding:6px 10px;">{{ $reservation['city'] ?? '' }}{{ (!empty($reservation['city']) && !empty($reservation['country'])) ? ', ' : '' }}{{ $reservation['country'] ?? '' }}</td>
    </tr>
    @endif
    @if(!empty($reservation['text']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Notes:</td>
        <td style="padding:6px 10px;">{{ $reservation['text'] }}</td>
    </tr>
    @endif
</table>
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
