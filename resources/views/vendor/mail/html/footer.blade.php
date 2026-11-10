{{-- climbing.ge mail footer: the mountains ridge (PNG of public/images/svg/mountains.svg —
     mail clients don't show SVG) over the #484848 footer band with the site links --}}
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
<td>
<table class="footer-wrap" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td style="padding: 0; line-height: 0; font-size: 0;">
<img src="{{ $base }}/images/email/mountains.png" class="footer-ridge" width="600" alt="">
</td>
</tr>
<tr>
<td class="footer-band">
<img src="{{ $base }}/images/email/logo-white.png" class="footer-logo" height="30" alt="{{ config('app.name') }}">
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
