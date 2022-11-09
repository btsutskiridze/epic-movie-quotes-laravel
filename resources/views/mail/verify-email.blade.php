<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
</head>

<body style="background: #181624; color:white;font-family: 'Montserrat', sans-serif; word-wrap: break-word; ">
    <div style="padding:2% 8%">
        <div style="text-align: center;font-size: 12px;margin-bottom: 73px">
            <div>
                @include('icons.quote-icon')
            </div>
            <h1 style="color:#DDCCAA; font-weight: 500">MOVIE QUOTES</h1>
        </div>
        <p style="margin-bottom: 24px">Hello {{ $user->name }} </p>
        <p style="margin-bottom: 32px">Thanks for joining Movie quotes! We really appreciate it. Please click the button
            below to verify your account:
        </p>
        <a href="{{ config('verification.url') . '?token=' . $user->token }}"
            style="max-width: 128px;padding:7px 13px;color:white;background:#E31221;text-decoration: none;font-weight: 400;
            border-radius:4px">Verify
            account</a>
        <p style="margin-bottom: 24px;margin-top:40px">If clicking doesn't work, you can try copying and pasting it to
            your
            browser:</p>
        <a href="{{ config('verification.url') . '?token=' . $user->token }}"
            style="margin-bottom: 40px;color:#DDCCAA;text-decoration: none;cursor: pointer">
            {{ config('verification.url') . '?token=' . $user->token }}</a>
        <p style="margin-bottom: 24px">If you have any problems, please contact us: support@moviequotes.ge</p>
        <p>MovieQuotes Crew</p>
    </div>
</body>

</html>
