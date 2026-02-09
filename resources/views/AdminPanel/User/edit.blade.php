<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Изменение-{{ $user->name }}</title>
</head>
<body>
<nav>
    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}">Назад</a>
</nav>
<br>

<form action="{{ route('admin.users.update', ['user' => $user->id]) }}" method="post" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <p>
        <input type="text" name="name" placeholder="Имя">
        <div>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </p>

    <p>
        <input type="text" name="email" placeholder="Email">
        <div>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </p>

    <p>
        <input type="submit" value="Отправить">
    </p>
</form>
</body>
</html>
