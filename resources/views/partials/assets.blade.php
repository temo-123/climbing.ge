{{-- Shared front-end assets for every subdomain layout.
     Bootstrap 5 is compiled into app.css from npm (resources/sass/app.scss),
     so no layout loads Bootstrap or jQuery from a CDN any more. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Manrope:wght@400..800&family=Noto+Sans+Georgian:wght@400..700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
