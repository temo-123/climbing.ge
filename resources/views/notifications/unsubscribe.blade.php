@php
$t = $ka ? [
  'title' => 'შეტყობინებების წერილები',
  'confirm_h' => 'გამოვრთოთ ყველა შეტყობინების წერილი?',
  'confirm_p' => 'აღარ მიიღებ წერილებს ახალ კონტენტზე, რჩეულებზე, ღონისძიებებზე, გამომწერებზე, კომენტარის პასუხებზე და სეზონურ რჩევებზე.',
  'keep' => 'ანგარიშის წერილები (პაროლი, შეკვეთები, ჯავშნები) მაინც მოვა.',
  'btn_off' => 'ყველას გამორთვა',
  'done_h' => 'მზადაა — შეტყობინებები გამორთულია',
  'done_p' => 'შეტყობინების წერილებს აღარ გამოგიგზავნით.',
  'already_h' => 'შეტყობინებები უკვე გამორთულია',
  'back_h' => 'შეტყობინებები ისევ ჩართულია',
  'back_p' => 'ისევ მიიღებ იმ შეტყობინებებს, რაც პარამეტრებში გაქვს არჩეული.',
  'btn_on' => 'ისევ ჩართვა',
  'manage' => 'ან აირჩიე კონკრეტული შეტყობინებები',
  'invalid_h' => 'ბმული არასწორია ან ვადაგასულია',
  'invalid_p' => 'შედი ანგარიშზე და შეცვალე შეტყობინებები პარამეტრებში.',
  'for' => 'ანგარიში:',
] : [
  'title' => 'Notification emails',
  'confirm_h' => 'Turn off all notification emails?',
  'confirm_p' => 'You won\'t get emails about new content, favourites, events, followers, comment replies or seasonal picks any more.',
  'keep' => 'Account emails (password, orders, bookings) still come.',
  'btn_off' => 'Turn them all off',
  'done_h' => 'Done — notifications are off',
  'done_p' => 'We won\'t send you notification emails any more.',
  'already_h' => 'Notifications are already off',
  'back_h' => 'Notifications are back on',
  'back_p' => 'You\'ll get the alerts you have chosen in your settings again.',
  'btn_on' => 'Turn them back on',
  'manage' => 'or choose exactly which emails you get',
  'invalid_h' => 'This link is not valid any more',
  'invalid_p' => 'Log in and change your notifications in your account settings.',
  'for' => 'Account:',
];
@endphp
<!doctype html>
<html lang="{{ $ka ? 'ka' : 'en' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>{{ $t['title'] }} · climbing.ge</title>
<style>
  :root { --ink:#15212b; --stone:#56646e; --chalk:#f5f3ee; --line:#e3ded4; --accent:#1f93b0; --danger:#c0392b; --card:#fff; }
  @media (prefers-color-scheme: dark) { :root { --ink:#eef1f3; --stone:#a7b2ba; --chalk:#11181e; --line:#2a343c; --card:#1a232a; } }
  * { box-sizing: border-box; }
  body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:16px; background:var(--chalk); color:var(--ink); font:16px/1.5 Manrope, 'Noto Sans Georgian', -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; }
  main { width:100%; max-width:480px; background:var(--card); border:1px solid var(--line); border-radius:14px; padding:28px 24px; }
  .logo { font-weight:800; letter-spacing:.02em; color:var(--accent); text-decoration:none; }
  h1 { font-size:1.35rem; margin:16px 0 8px; }
  p { margin:0 0 12px; color:var(--stone); }
  .who { font-size:.9rem; }
  .btn { display:inline-block; border:0; border-radius:999px; padding:10px 20px; font:inherit; font-weight:700; cursor:pointer; text-decoration:none; }
  .btn-off { background:var(--danger); color:#fff; }
  .btn-on { background:var(--accent); color:#fff; }
  form { margin:16px 0 12px; }
  a { color:var(--accent); }
</style>
</head>
<body>
<main>
  <a class="logo" href="{{ config('app.base_url_ssh') }}">climbing.ge</a>

  @if($state === 'invalid')
    <h1>{{ $t['invalid_h'] }}</h1>
    <p>{{ $t['invalid_p'] }}</p>
    <p><a href="{{ $manage_url }}">{{ $t['manage'] }}</a></p>
  @else
    @if($state === 'confirm')
      <h1>{{ $t['confirm_h'] }}</h1>
      <p>{{ $t['confirm_p'] }}</p>
    @elseif($state === 'done')
      <h1>{{ $t['done_h'] }}</h1>
      <p>{{ $t['done_p'] }}</p>
    @elseif($state === 'already_off')
      <h1>{{ $t['already_h'] }}</h1>
    @elseif($state === 'back_on')
      <h1>{{ $t['back_h'] }}</h1>
      <p>{{ $t['back_p'] }}</p>
    @endif

    <p class="who">{{ $t['for'] }} {{ $email }}</p>
    <p class="who">{{ $t['keep'] }}</p>

    <form method="post" action="{{ $action_url }}">
      @if(in_array($state, ['confirm', 'back_on']))
        <button class="btn btn-off" type="submit" name="action" value="off">{{ $t['btn_off'] }}</button>
      @else
        <button class="btn btn-on" type="submit" name="action" value="on">{{ $t['btn_on'] }}</button>
      @endif
    </form>
    <p><a href="{{ $manage_url }}">{{ $t['manage'] }}</a></p>
  @endif
</main>
</body>
</html>
