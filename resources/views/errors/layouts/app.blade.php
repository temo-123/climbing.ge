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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 50px;
        }

        /* ---- Navbar — matches Vue .navbar.navbar-inverse.navbar-fixed-top.bg-dark ---- */
        .navbar {
            background-color: #222;
            border: none;
            border-radius: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            min-height: 50px;
            display: flex;
            align-items: center;
        }
        .navbar .container-fluid {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0 15px;
        }
        .navbar .navbar-header {
            display: flex;
            align-items: center;
        }
        .site_title {
            margin-left: 1em;
            color: #fff;
            font-size: 135%;
            white-space: nowrap;
            display: inline-block;
            text-decoration: none;
        }
        .site_title:hover { color: #ccc; }
        .navbar span { color: #fff; }
        .navbar span:hover { color: #838383; transition: 0.2s; }

        /* ---- Error card ---- */
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
            margin-bottom: 8px;
            object-fit: cover;
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

    <nav class="navbar navbar-inverse navbar-fixed-top bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="https://climbing.ge" class="site_title">
                    <span>climbing.ge</span>
                </a>
            </div>
        </div>
    </nav>

    @yield('content')

    <div class="site-footer">&copy; {{ date('Y') }} climbing.ge</div>

</body>
</html>
