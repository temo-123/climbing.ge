<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Error') — climbing.ge</title>

    <link href="{{ asset('assets/img/site_logo/x.png') }}" rel="shortcut icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .error-topbar {
            background-color: #bb2727;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .error-topbar .brand {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .error-topbar .go-back {
            color: #fff;
            text-decoration: none;
            font-size: 0.95rem;
            opacity: 0.9;
        }
        .error-topbar .go-back:hover { opacity: 1; text-decoration: underline; }
        .error-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 16px;
        }
        .error-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            padding: 48px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            color: #bb2727;
            line-height: 1;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 12px;
        }
        .error-desc {
            color: #666;
            margin-bottom: 28px;
        }
        .error-img {
            width: 100%;
            max-width: 420px;
            border-radius: 8px;
            margin-bottom: 28px;
            object-fit: cover;
        }
        .btn-back {
            background-color: #bb2727;
            border-color: #bb2727;
            color: #fff;
            padding: 10px 28px;
            border-radius: 6px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            background-color: #9e1f1f;
            border-color: #9e1f1f;
            color: #fff;
        }
        .site-footer {
            text-align: center;
            padding: 16px;
            color: #aaa;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="error-topbar">
        <a href="https://climbing.ge" class="brand">climbing.ge</a>
        <a href="{{ URL::previous() }}" class="go-back">&larr; Go Back</a>
    </div>

    @yield('content')

    <div class="site-footer">&copy; {{ date('Y') }} climbing.ge</div>
</body>
</html>
