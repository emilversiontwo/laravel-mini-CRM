<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Создание нового пользователя</title>
</head>
<body>
<nav>
    <a href="{{ route('admin.users.index') }}">Назад</a>
</nav>
<br>

<form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div>
        <input type="text" name="name" placeholder="Имя">
        <div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div>
        <input type="text" name="email" placeholder="Email">
        <div>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div>
        <input type="text" name="password" placeholder="Пароль">
        <div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div>
        <input type="submit" value="Создать">
    </div>
</form>
</body>
</html>
