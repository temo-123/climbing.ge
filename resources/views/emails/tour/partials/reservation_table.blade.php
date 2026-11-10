{{-- Trip summary card, shared by the tour reservation mails. $show_phone = also phone + location + guide,
     $show_fee = the climbing.ge fee box (staff mails only — never the guest). --}}
@if(isset($reservation) && $reservation)
<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;margin:18px 0;border-collapse:separate;border:1px solid #e3ded4;border-radius:10px;background:#f5f3ee;">
@if(!empty($reservation['tour']))
<tr><td colspan="2" style="padding:14px 18px 10px;border-bottom:1px solid #e3ded4;"><span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#87939b;">Tour</span><br><strong style="font-size:17px;color:#15212b;">{{ $reservation['tour'] }}</strong></td></tr>
@endif
@if(!empty($reservation['check_in']))
<tr><td style="padding:9px 18px 0;width:130px;color:#87939b;font-size:14px;">📅 Date</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;font-weight:bold;">@php($ci = rescue(fn () => \Carbon\Carbon::parse($reservation['check_in']), null, false)){{ $ci ? $ci->format(strlen($reservation['check_in']) > 10 ? 'D, j M Y, H:i' : 'D, j M Y') : $reservation['check_in'] }}</td></tr>
@endif
@if(!empty($reservation['days']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">⏱ Duration</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;font-weight:bold;">{{ $reservation['days'] }} {{ (int) $reservation['days'] === 1 ? 'day' : 'days' }}</td></tr>
@endif
@if(!empty($reservation['persons']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">👥 Group</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;font-weight:bold;">{{ $reservation['persons'] }} {{ (int) $reservation['persons'] === 1 ? 'person' : 'persons' }}</td></tr>
@endif
@if(!empty($reservation['language']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">🗣 Language</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;font-weight:bold;">{{ $reservation['language'] }}</td></tr>
@endif
@if(!empty($reservation['name']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">🙋 Name</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;">{{ $reservation['name'] }}</td></tr>
@endif
@if(!empty($reservation['email']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">✉️ Email</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;">{{ $reservation['email'] }}</td></tr>
@endif
@if(!empty($reservation['phone']) && !empty($show_phone))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">📞 Phone</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;">{{ $reservation['phone'] }}</td></tr>
@endif
@if((!empty($reservation['country']) || !empty($reservation['city'])) && !empty($show_phone))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">📍 From</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;">{{ $reservation['city'] ?? '' }}{{ (!empty($reservation['city']) && !empty($reservation['country'])) ? ', ' : '' }}{{ $reservation['country'] ?? '' }}</td></tr>
@endif
@if(!empty($reservation['guide']['name']) && !empty($show_phone))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">🧭 Guide</td><td style="padding:9px 18px 0;color:#15212b;font-size:14px;font-weight:bold;">{{ $reservation['guide']['name'] }}@if(!empty($reservation['guide']['email']))<br><span style="font-weight:normal;color:#56646e;">{{ $reservation['guide']['email'] }}@if(!empty($reservation['guide']['phone'])) · {{ $reservation['guide']['phone'] }}@endif</span>@endif</td></tr>
@endif
@if(!empty($reservation['text']))
<tr><td colspan="2" style="padding:12px 18px 0;"><span style="color:#87939b;font-size:14px;">💬 Message</span><div style="margin-top:4px;padding:10px 12px;border-radius:8px;background:#ffffff;color:#24343f;font-size:14px;line-height:1.5;">{!! nl2br(e($reservation['text'])) !!}</div></td></tr>
@endif
@if(array_key_exists('price', $reservation))
<tr><td colspan="2" style="padding:14px 18px 16px;"><div style="padding:12px 14px;border-radius:8px;background:#e6f4ee;">@if(!empty($reservation['price']))<span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#1f7a4d;">Price</span><br><strong style="font-size:22px;color:#15212b;">{{ $reservation['price']['total'] }}</strong><br><span style="font-size:13px;color:#4f6b5d;">{{ $reservation['price']['breakdown'] }}</span>@else<span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#1f7a4d;">Price</span><br><strong style="font-size:16px;color:#15212b;">On request</strong><br><span style="font-size:13px;color:#4f6b5d;">The guide will tell you the price for your group.</span>@endif</div></td></tr>
@else
<tr><td colspan="2" style="padding:0 0 12px;"></td></tr>
@endif
@if(!empty($show_fee) && !empty($reservation['commission']))
<tr><td colspan="2" style="padding:0 18px 16px;"><div style="padding:12px 14px;border-radius:8px;background:#fff3cd;border:1px solid #f0d58a;"><span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#8a6100;">climbing.ge fee · {{ $reservation['commission']['percent'] }}%</span><br><strong style="font-size:18px;color:#15212b;">{{ $reservation['commission']['amount'] ?? ($reservation['commission']['percent'] . '% of the final price') }}</strong>@if(!empty($reservation['commission']['paid']))<span style="margin-left:6px;color:#1f7a4d;font-weight:bold;">✓ paid</span>@endif<br><span style="font-size:13px;color:#6b5a2b;">This reservation was passed to a guide who isn't the tour's general guide, so {{ $reservation['commission']['percent'] }}% of the total goes to climbing.ge — please pay it after the tour.</span></div></td></tr>
@endif
</table>
@endif
