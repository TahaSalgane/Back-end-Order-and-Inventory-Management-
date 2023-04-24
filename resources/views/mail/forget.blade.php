<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forget PAssword</title>
</head>
<body>
    Hi {{ $name }}<br>
    Chage your Password <a href='http://localhost:3000/reset/{{ $data }}'>Click Here</a>
    <br>
    PinCode : {{ $data }}
</body>
</html>