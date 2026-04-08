<h1 align="center">Climbing.ge User Panel (user.climbing.ge)</h1>

<h1>Registration and login</h1>

<p>To register, fill the registration form. API sends confirmation email.</p>

<p>After confirmation, API creates <code>user_role</code> and <code>user_notifications</code> records. All notifications default active. Before that, user service (without role) is impossible.</p>

<p>If no email received, resend.</p>

<h2>Password reset</h2>

<p>Click "Forgot password" on login. Enter email. If user exists, email sent.</p>

<p>Click link and set new password.</p>

<h1>Social login</h1>

<p>Login with Google or Facebook. Config in .env.example.</p>

<h2>Logout</h2>

<p>If server returns 419/401 on one request, user dumped to login (session/cookie cleared).</p>

<h1>Permissions &amp; Roles</h1>

<p>Uses standard Laravel permissions.</p>

<p>Roles/permissions in DB tables.</p>

<img src="docs/DEMO_IMAGES/User_and_admin/Laravel_role_permission.png" alt="Laravel roles/permissions">

<p>More: [laravel.com](https://laravel.com/)</p>

<h2>Roles on frontend</h2>

<p>Uses Vue plugin "casl-vue". See official docs.</p>

[casl-vue](https://casl.js.org/v5/en/package/casl-vue)

<h1>User and services followers notification</h1>

<h2>Queues</h2>

<p>English docs: [Laravel Queues](https://laravel.com/docs/10.x/queues#main-content)</p>
<p>Russian docs: [Laravel Queues RU](https://laravel.su/docs/8.x/queues#job-events)</p>

<p>Uses Laravel Queues for bulk emails (background).</p>

<p>Uses <code>jobs</code> table.</p>

<p>Config .env: <code>QUEUE_CONNECTION=database</code>.</p>

<p>Run email queue (prefix onQueue('emails')):</p>

```bash
php artisan queue:work --queue=emails
```

<p>Running commands:</p>

```bash
php artisan queue:work  # watch forever
php artisan queue:work --timeout=60  # every 60s
```

<h2>Laravel Task Scheduling</h2>

<p>English: [Scheduling](https://laravel.com/docs/10.x/scheduling#main-content)</p>
<p>Russian: [Scheduling RU](https://laravel.su/docs/8.x/scheduling#running-the-scheduler-locally)</p>

<p>Automatic event notifications. See <code>app/Console/Kernel.php</code> schedule(). Creates queues in <code>app/Jobs/UserNotifications.php</code>.</p>

<p>Set timezone in <code>config/app.php</code> (<code>'Asia/Tbilisi'</code> for Georgia).</p>

<p>Test command:</p>

```bash
php artisan send_event_notification:users
```

<p>List tasks:</p>

```bash
php artisan schedule:list
```

<p>Run scheduler watch:</p>

```bash
php artisan schedule:work
```

<hr>

[Go back](../README.md)

