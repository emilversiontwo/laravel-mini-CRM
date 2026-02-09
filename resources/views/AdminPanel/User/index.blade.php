<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User-Index</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Назад</a>
    </nav>

    <br>

    @foreach($users as $user)
        <article>
            <div>
                <p>Имя: {{ $user->name }}</p>
                <p>Почта: {{ $user->email }}</p>
                <a href="{{ route('admin.users.show', ['user' => $user->id]) }}">Открыть</a>
            </div>
        </article>
        <hr />
    @endforeach

    <nav>
        <a href="{{ route('admin.users.create') }}">Создать</a>
    </nav>
</body>
</html>
