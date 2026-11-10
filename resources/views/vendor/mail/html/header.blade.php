@props(['url'])
{{-- climbing.ge mail header: granite band + white logo, then the blue/yellow rope (like the site) --}}
<tr>
<td class="header">
<table class="inner-top" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="header-band" colspan="2">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<img src="{{ rtrim(config('app.url'), '/') }}/images/email/logo-white.png" class="header-logo" height="44" alt="{{ config('app.name') }}">
</a>
<div class="header-tagline">Climbing in Georgia</div>
</td>
</tr>
<tr>
<td class="rope-blue" width="70%" height="4">&nbsp;</td>
<td class="rope-hold" width="30%" height="4">&nbsp;</td>
</tr>
</table>
</td>
</tr>
