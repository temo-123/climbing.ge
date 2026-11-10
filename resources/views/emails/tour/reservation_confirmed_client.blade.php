@component('mail::message')
# Your reservation is confirmed

Hello {{ $reservation['name'] ?? '' }},

thank you — your tour reservation is confirmed. The guide got it and will contact you soon to agree on the details.

## Your trip

@include('emails.tour.partials.reservation_table', ['reservation' => $reservation, 'show_phone' => true])

@if(!empty($reservation['tour_info']))
<br>

## About the tour

**{{ $reservation['tour_info']['title'] }}**

@if(mb_strlen($reservation['tour_info']['short_description'] ?? '') > 3)
{{ $reservation['tour_info']['short_description'] }}
@endif

@if(!empty($reservation['tour_info']['location']))
**Location:** {{ $reservation['tour_info']['location'] }}<br>
@endif
@if(!empty($reservation['tour_info']['duration']))
**Tour length:** {{ $reservation['tour_info']['duration'] }}
@endif

@component('mail::button', ['url' => $reservation['tour_info']['url']])
Open the tour page
@endcomponent
@endif

@if(!empty($reservation['guides']))
## Your guide

@foreach($reservation['guides'] as $guide)
**{{ $guide['name'] }}**@if(!empty($guide['email'])) · {{ $guide['email'] }}@endif @if(!empty($guide['phone'])) · {{ $guide['phone'] }}@endif<br>
@endforeach
@endif

@if(empty($reservation['price']))
<p>The price for your group is set by the guide — they will tell you when they contact you.</p>
@else
<p style="color:#87939b;font-size:13px;">The price is calculated from the tour's price list for your duration and group size. Payment is agreed with the guide.</p>
@endif

<p>Need to change something? Just reply to this email.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
