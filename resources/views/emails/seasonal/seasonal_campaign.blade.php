@component('mail::message')
# {{ $d['subject'] }}

@if(!empty($d['name']))
{{ $l['hi'] }} {{ $d['name'] }},
@endif

@if(!empty($d['intro']))
{{ $d['intro'] }}
@endif

@foreach($d['items'] as $item)
@component('mail::panel')
@if(!empty($item['image']))
<a href="{{ $item['url'] }}"><img src="{{ $item['image'] }}" alt="" width="520" style="display:block;width:100%;max-width:520px;height:auto;border-radius:8px;margin:0 0 12px;"></a>
@endif
**[{{ $item['title'] }}]({{ $item['url'] }})**

@if(!empty($item['text']))
{{ $item['text'] }}
@endif

[{{ $l['open'] }} →]({{ $item['url'] }})
@endcomponent
@endforeach

<p style="font-size:13px;color:#87939b;">{{ $l['why'] }} <a href="{{ $d['manage_url'] }}" style="color:#87939b;text-decoration:underline;">{{ $l['manage'] }}</a>@if(!empty($d['unsubscribe_url'])) · <a href="{{ $d['unsubscribe_url'] }}" style="color:#87939b;text-decoration:underline;">{{ $l['all_off'] }}</a>@endif</p>

{{ $l['bye'] }}<br>
{{ $l['team'] }}
@endcomponent
