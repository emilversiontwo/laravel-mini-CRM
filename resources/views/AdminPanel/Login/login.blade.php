<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form action="{{ route('authenticate') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p>
            <input type="text" name="email" placeholder="Email">
        </p>
        <p>
            <input type="password" name="password" placeholder="Пароль">
        </p>
        <p>
            <input type="submit" value="Войти">
        </p>
    </form>
</body>
</html>
