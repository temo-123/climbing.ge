@props(['url'])
{{-- climbing.ge mail header — full width like the site navbar: granite band + white logo,
     then the blue / yellow rope. Images are embedded (CID) by MailGuardService. --}}
<tr>
<td class="header" style="padding: 0;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="header-band" colspan="2" align="center" bgcolor="#15212b">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<img src="{{ rtrim(config('app.url'), '/') }}/images/email/logo-white.png" class="header-logo" width="150" height="44" alt="{{ config('app.name') }}">
</a>
<div class="header-tagline">Climbing in Georgia</div>
</td>
</tr>
<tr>
<td class="rope-blue" width="70%" height="4" bgcolor="#1f93b0">&nbsp;</td>
<td class="rope-hold" width="30%" height="4" bgcolor="#ffc233">&nbsp;</td>
</tr>
</table>
</td>
</tr>
