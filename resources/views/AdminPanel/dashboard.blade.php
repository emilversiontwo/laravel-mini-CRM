<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AdminPanel-Dashboard</title>
</head>
<body>
    <div>
        <h1>Admin Panel</h1>

        <nav>
            <a href="{{ route('admin.tickets.index') }}">Рассмотрение заявок</a>
            <br>
            <a href="{{ route('admin.users.index') }}">Управление пользователями</a>
        </nav>

        <small>Быстрые ссылки управления</small>
        <br>

        <form action="{{ route('logout') }}" method="post">
            @csrf
            <input type="submit" value="Выйти">
        </form>
    </div>
</body>
</html>
