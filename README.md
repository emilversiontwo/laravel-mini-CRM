# Laravel Mini-CRM

## Описание

Мини-CRM для приёма и обработки заявок с сайта через универсальный виджет. Включает административную панель и базовую статистику.

## Требования

* Git
* Docker
* Docker Compose v2
* Taskfile - [https://taskfile.dev/docs/installation](https://taskfile.dev/docs/installation)  
  Рекомендуемая ОС - Linux.

## Переменные окружения (обязательные)

* `APP_PORT` - порт приложения (по умолчанию `8080`).
* `DB_PORT` - внутренний порт в сети Docker (`5432`). Не менять при стандартной конфигурации.
* `DB_EXTERNAL_PORT` - внешний проброшенный порт для доступа к БД с хоста (например, DataGrip).
* Остальные стандартные переменные Laravel: `APP_ENV`, `APP_KEY`, `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

## Быстрый старт (Linux)

Клонировать и перейти в репозиторий:

```bash
git clone https://github.com/emilversiontwo/laravel-mini-CRM.git
cd laravel-mini-CRM
````

Первичная настройка (сборка, зависимости, миграции, сиды):

```bash
task setup
```

Поднять окружение:

```bash
task up
```

Остановить и удалить контейнеры:

```bash
task down
```

## Доступ

- API: `http://localhost:8080/api` (при `APP_PORT=8080`).

- Swagger: `http://localhost:8080/api/documentation#/` (при `APP_PORT=8080`).

- Admin Panel: `http://localhost:8080/login` (при `APP_PORT=8080`).  
  Дефолтные данные для входа: логин: `admin@admin.com`, пароль: `password`.

- HTML-код для вставки виджета:
  (при `APP_PORT=8080`)
```html
<iframe src="http://127.0.0.1:8080/feedback-widget" frameborder="0" scrolling="yes" width="500" height="500">
  Ваш браузер не поддерживает фреймы!
</iframe>
```

## Команды

- Запуск тестов:

```bash
task artisan -- test
```

- Выполнение произвольной artisan-команды:

```bash
task artisan -- <command>
```

- Выполнение произвольной composer-команды:

```bash
task composer -- <command>
```

- Генерация Swagger-документации:

```bash
task docgen
```

## Работа с БД

- Внутренний хост: `db` (имя сервиса в docker compose).

- Внешнее подключение: `localhost:<DB_EXTERNAL_PORT>`.  
  При конфликте портов измените `DB_EXTERNAL_PORT`.

## Тестирование

Запуск тестов:

```bash
task artisan -- test
```

## Почему и зачем

**Зачем в админке разделение на обращения по API и по обычному?**  
Причина - требование в ТЗ выделить API-маршрут для статистики заявок.

**Почему есть только Feature тесты, а Unit тестов нет?**  
Исходя из того, что это не реальный продакшн-проект, я не стал полностью покрывать код тестами и ограничился Feature-тестами для быстрой проверки работоспособности приложения.

**Зачем явное разграничение Customer и Ticket и жёсткая валидация номера телефона и почты Customer?**  
Это требование из ТЗ - разнести сущности и, по возможности, добавить кулдаун/таймаут для повторной отправки. Жёсткая валидация задана для минимизации мусорных/некорректных заявок.

## Что можно улучшить

Можно чётко разделить фронтенд и бэкенд на два Docker контейнера: фронтенд - Vue.js/SPA, бэкенд - Laravel-приложение.  
В чём преимущество такого подхода: мы чётко разграничиваем зоны ответственности, благодаря чему приложение становится гибче при росте и проще в сопровождении.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
