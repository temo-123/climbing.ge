{{-- climbing.ge mail footer — full width like the site footer: the mountains ridge
     (public/images/email/mountains.png, a PNG of images/svg/mountains.svg — mail clients don't
     show SVG; embedded as CID by MailGuardService) over the #484848 band with the links. --}}
@php
    $base = rtrim(config('app.url'), '/');
    $ssh = env('APP_SSH', 'https://');
    $links = [
        'Guidebook' => $ssh . env('SITE_URL', 'climbing.ge'),
        'Shop' => $ssh . env('SHOP_URL', 'shop.climbing.ge'),
        'Blog' => $ssh . env('BLOG_URL', 'blog.climbing.ge'),
        'Summits' => $ssh . env('SUMMIT_URL', 'summit.climbing.ge'),
    ];
@endphp
<tr>
<td style="padding: 32px 0 0; line-height: 0; font-size: 0;" bgcolor="#f5f3ee">
<img src="{{ $base }}/images/email/mountains.png" class="footer-ridge" width="1200" alt="" style="display:block;width:100%;max-width:100%;height:auto;border:0;">
</td>
</tr>
<tr>
<td class="footer-band" align="center" bgcolor="#484848">
<table class="footer-inner" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center" style="padding: 4px 24px 30px;">
<img src="{{ $base }}/images/email/logo-white.png" class="footer-logo" width="102" height="30" alt="{{ config('app.name') }}">
<p class="footer-links" style="margin: 0;">
@foreach($links as $label => $href)
@if(!$loop->first)<span class="footer-dot">•</span>@endif<a href="{{ $href }}">{{ $label }}</a>
@endforeach
</p>
<p class="footer-note">{{ Illuminate\Mail\Markdown::parse($slot) }}</p>
<p class="footer-copy">© {{ date('Y') }} {{ config('app.name') }} · Climbing in Georgia</p>
</td>
</tr>
</table>
</td>
</tr>
