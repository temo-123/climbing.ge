<h1 align="center">Climbing.ge user panel (user.climbing.ge)</h1>

<h1>Registration and login</h1> 

<p>For register need insert your information in registration page. After registration API send confirming message.</p>

<p>After confirming API create user_role and user_notificaions tabs in DB. All notifications defolt is activw. Beore thet us service (without role) is inpaseble.</p>

<p>If you dont take message you can send new.</p>

<h2>Password reset</h2>

<p>For reset password ckick button "Return password" on login page. After insert your mail. Service check if user of this mail is isset it send mail on this mail</p>

<p>After user need click to message and insetr new password</p>

<h1>Social login</h1>

<p>Poseble login with google and facebook</p>

<p>Configuration for social login can find in .env file (check .env example file)</p>

<h2>Go out</h2>

<p>If server return only 1 request with 419 or 401 error, user dumpd in login page and mist sesion and cucie</p>

<h1>Permissions & Roles</h1> 

<p>This project use standard laravel permissions structure.</p>

<p>Roles and permissions are inserted in DB tabs</p>

![Laravel role and permission](/docs/Demo_images/User_and_admin/Laravel_role_permission.png)

<p>Visit laravel official site for more information</p>

[laravel.com](https://laravel.com/)

<h2>Roles on front</h2>

<p>For permissions in front-end use VUE plugin -> "casl-vue".</p>

<p>More information about this plugin show in official documentation.</p> 

[casl-vue oficial site](https://casl.js.org/v5/en/package/casl-vue)

<h1>User and services followers notification</h1>

<h2>Queues</h2>

English documentatione [here](https://laravel.com/docs/10.x/queues#main-content) 
Russion documentatione [here](https://laravel.su/docs/8.x/queues#job-events)

<p>For sending lot of messages project us "Laravel Queues". It make notification quenes in DB and confirm it on event data. This is done so that you do not wait until all messages are sent, they will be sent in the background!</p> 

<p>In DB use tab "jobs".</p> 

<p>Config in .env => "QUEUE_CONNECTION=jobs".</p> 

<p>
For run email queue use (whith prefix onQueue('emails')) command ->

```bash
php artisan queue:work --queue=emails
``` 
</p>

<p>
Runing command ->

```bash
php artisan queue:work
``` 
- run for all time watch, 

```bash
php artisan queue:work –timeout=60
``` 
- run all 60 secund, 
</p>

<h2>Laravel Advanced - Task Scheduling</h2>

English documentatione [here](https://laravel.com/docs/10.x/scheduling#main-content) 
Russion documentatione [here](https://laravel.su/docs/8.x/scheduling#running-the-scheduler-locally)

<p>For send event notificatione automatcly project use "Laravel Advanced". It keeps track of the send time and execute it at the given time.</p> 

<p>You can fined all this objects in "app/Console/Kernel.php", in function "schedule". After function in "app/Console/Comands/UserAutoNotificatione" create queues (file -> app/Jobs/EventAutoNotificatione.php) </p>

<p>For corect working necessary inser corect timexone in "config/app.php" and check parameter "timezone". (For Georgia "'Asia/Tbilisi'")</p>

<p>
For run sending from comand line -> 

```bash
send_event_notificatione:users
``` 
</p>

<p>
For check all taks run command in consol ->

```bash
php artisan schedule:list
``` 
</p>

<p>
Run command for watch tasks->

```bash
php artisan schedule:work
``` 
</p>

<!-- CSRF Token Mismatch On Axios error fixing - https://stackoverflow.com/questions/74340913/csrf-token-mismatch-on-axios-post-requests-in-laravel -->

<hr>

[Go back](../README.md) - to README.md