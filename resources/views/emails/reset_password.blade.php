<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} Password Reset</title>
</head>
<body>
    <h2>{{ config('app.name') }} Password Reset</h2>
    <p>Click the following link to reset your password:</p>
    <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Thank you,</p>
    <p>{{ config('app.name') }} Team</p>
</body>
</html>
