<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пользователь-{{ $user->name }}</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.users.index') }}">Назад</a>
    </nav>

    <article>
        <p>Имя: {{ $user->name }}</p>
        <p>Почта: {{ $user->email }}</p>
        <p>Роль: {{ $user->getRoleNames()[0] }}</p>
        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}">Изменить</a>
        <form action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="post">
            @method('DELETE')
            @csrf
            <input type="submit" value="Удалить">
        </form>
        <a href="{{ route('admin.users.reset-password', ['user' => $user->id]) }}">Сменить пароль</a>
    </article>
</body>
</html>
