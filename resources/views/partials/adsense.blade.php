@if(config('adsense.publisher_id'))
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('adsense.publisher_id') }}" crossorigin="anonymous"></script>
@endif
