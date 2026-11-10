{{-- Reservation details table, shared by the tour reservation mails --}}
@if(isset($reservation) && $reservation)
<table style="width:100%;border-collapse:collapse;margin-top:15px;">
    @if(!empty($reservation['tour']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;width:130px;">Tour:</td>
        <td style="padding:6px 10px;">{{ $reservation['tour'] }}</td>
    </tr>
    @endif
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
    @if(!empty($reservation['phone']) && !empty($show_phone))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Phone:</td>
        <td style="padding:6px 10px;">{{ $reservation['phone'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['check_in']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Check In:</td>
        <td style="padding:6px 10px;">{{ str_replace('T', ' ', $reservation['check_in']) }}</td>
    </tr>
    @endif
    @if(!empty($reservation['persons']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Persons:</td>
        <td style="padding:6px 10px;">{{ $reservation['persons'] }}</td>
    </tr>
    @endif
    @if(!empty($reservation['days']))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Duration:</td>
        <td style="padding:6px 10px;">{{ $reservation['days'] }} {{ (int) $reservation['days'] === 1 ? 'day' : 'days' }}</td>
    </tr>
    @endif
    @if(array_key_exists('price', $reservation))
    <tr>
        <td style="padding:6px 10px;font-weight:bold;">Price:</td>
        <td style="padding:6px 10px;">
            @if(!empty($reservation['price']))
                <strong>{{ $reservation['price']['total'] }}</strong><br>
                <span style="color:#87939b;font-size:13px;">{{ $reservation['price']['breakdown'] }}</span>
            @else
                On request — the guide will tell you the price
            @endif
        </td>
    </tr>
    @endif
    @if((!empty($reservation['country']) || !empty($reservation['city'])) && !empty($show_phone))
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
