<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>reCAPTCHA</title>
</head>
<body>
    @if($siteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ $siteKey }}', { action: 'submit' }).then(function (token) {
                    if (window.ReactNativeWebView) {
                        window.ReactNativeWebView.postMessage(token);
                    }
                }).catch(function () {
                    if (window.ReactNativeWebView) {
                        window.ReactNativeWebView.postMessage('');
                    }
                });
            });
        </script>
    @endif
</body>
</html>
