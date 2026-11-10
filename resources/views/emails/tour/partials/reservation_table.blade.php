{{-- Trip summary card, shared by the tour reservation mails. $show_phone = also phone + location. --}}
@if(isset($reservation) && $reservation)
<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;margin:18px 0;border-collapse:separate;border:1px solid #e3e8ec;border-radius:10px;background:#f7f9fa;">
@if(!empty($reservation['tour']))
<tr><td colspan="2" style="padding:14px 18px 10px;border-bottom:1px solid #e3e8ec;"><span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#87939b;">Tour</span><br><strong style="font-size:17px;color:#1c2730;">{{ $reservation['tour'] }}</strong></td></tr>
@endif
@if(!empty($reservation['check_in']))
<tr><td style="padding:9px 18px 0;width:130px;color:#87939b;font-size:14px;">📅 Date</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;font-weight:bold;">@php($ci = rescue(fn () => \Carbon\Carbon::parse($reservation['check_in']), null, false)){{ $ci ? $ci->format(strlen($reservation['check_in']) > 10 ? 'D, j M Y, H:i' : 'D, j M Y') : $reservation['check_in'] }}</td></tr>
@endif
@if(!empty($reservation['days']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">⏱ Duration</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;font-weight:bold;">{{ $reservation['days'] }} {{ (int) $reservation['days'] === 1 ? 'day' : 'days' }}</td></tr>
@endif
@if(!empty($reservation['persons']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">👥 Group</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;font-weight:bold;">{{ $reservation['persons'] }} {{ (int) $reservation['persons'] === 1 ? 'person' : 'persons' }}</td></tr>
@endif
@if(!empty($reservation['name']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">🙋 Name</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;">{{ $reservation['name'] }}</td></tr>
@endif
@if(!empty($reservation['email']))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">✉️ Email</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;">{{ $reservation['email'] }}</td></tr>
@endif
@if(!empty($reservation['phone']) && !empty($show_phone))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">📞 Phone</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;">{{ $reservation['phone'] }}</td></tr>
@endif
@if((!empty($reservation['country']) || !empty($reservation['city'])) && !empty($show_phone))
<tr><td style="padding:9px 18px 0;color:#87939b;font-size:14px;">📍 From</td><td style="padding:9px 18px 0;color:#1c2730;font-size:14px;">{{ $reservation['city'] ?? '' }}{{ (!empty($reservation['city']) && !empty($reservation['country'])) ? ', ' : '' }}{{ $reservation['country'] ?? '' }}</td></tr>
@endif
@if(!empty($reservation['text']))
<tr><td colspan="2" style="padding:12px 18px 0;"><span style="color:#87939b;font-size:14px;">💬 Message</span><div style="margin-top:4px;padding:10px 12px;border-radius:8px;background:#ffffff;color:#3d4a54;font-size:14px;line-height:1.5;">{!! nl2br(e($reservation['text'])) !!}</div></td></tr>
@endif
@if(array_key_exists('price', $reservation))
<tr><td colspan="2" style="padding:14px 18px 16px;"><div style="padding:12px 14px;border-radius:8px;background:#e6f4ee;">@if(!empty($reservation['price']))<span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#1f7a4d;">Price</span><br><strong style="font-size:22px;color:#1c2730;">{{ $reservation['price']['total'] }}</strong><br><span style="font-size:13px;color:#4f6b5d;">{{ $reservation['price']['breakdown'] }}</span>@else<span style="font-size:12px;letter-spacing:.06em;text-transform:uppercase;color:#1f7a4d;">Price</span><br><strong style="font-size:16px;color:#1c2730;">On request</strong><br><span style="font-size:13px;color:#4f6b5d;">The guide will tell you the price for your group.</span>@endif</div></td></tr>
@else
<tr><td colspan="2" style="padding:0 0 12px;"></td></tr>
@endif
</table>
@endif
