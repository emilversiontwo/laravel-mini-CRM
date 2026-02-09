<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Изменение пароля</title>
</head>
<body>
<nav>
    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}">Назад</a>
</nav>
<br>

<form action="{{ route('admin.users.updatePassword', ['user' => $user->id]) }}" method="post"
      enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div>
        <input type="text" name="password" placeholder="Новый пароль">
        <div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div>
        <input type="submit" value="Отправить">
    </div>
</form>
</body>
</html>
