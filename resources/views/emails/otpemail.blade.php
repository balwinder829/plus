<!DOCTYPE html>
<html>
<head>
    <title>+Plus</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
    <p><a href="/verify/{{ $details['otp'] }}"></a></p>
    <p>Thank you</p>
</body>
</html>