# NG Test Laravel - ТЗ PHP Developer (Laravel)

## Стек

- PHP 8.3, Laravel 13
- MySQL 8
- nginx + php-fpm
- Docker / docker-compose

## Запуск

1. Скопіювати файл оточення:

   ```
   cp .env.example .env
   ```

2. Зібрати та підняти контейнери:

   ```
   docker compose up -d --build
   ```

3. Встановити залежності composer:

   ```
   docker compose exec app composer install
   ```

4. Згенерувати APP_KEY:

   ```
   docker compose exec app php artisan key:generate
   ```

5. Застосувати міграції БД:

   ```
   docker compose exec app php artisan migrate
   ```

6. Відкрити застосунок у браузері:

   ```
   http://localhost:8080
   ```

## Adminer (опційно)

Веб-інтерфейс до БД доступний на `http://localhost:8081`. Дані для входу:

- Система: MySQL
- Сервер: `mysql`
- Користувач / Пароль / База даних: значення з `.env`

## Функціонал

- На головній сторінці - форма реєстрації (Username, Phone number).
- Після успішної реєстрації видається унікальне посилання на окрему сторінку ("сторінка А"), дійсне 7 днів. Після цього посилання перестає працювати.
- На сторінці А доступно:
  - перегенерувати поточне посилання (видає новий токен і скидає термін дії на 7 днів наново);
  - деактивувати поточне посилання;
  - натиснути "Im feeling lucky" - отримати випадкове число (1–1000), результат Win/Lose (парне → Win, непарне → Lose) і суму виграшу (0 при Lose; інакше 10%/30%/50%/70% від числа залежно від діапазону);
  - натиснути "History" - побачити останні 3 результати "Im feeling lucky".

## Структура проєкту

- `app/Http/Controllers` - обробка HTTP-запитів (реєстрація, дії сторінки А).
- `app/Models` - Eloquent-моделі (`Registration`, `GameResult`) з роботою з БД.
- `app/Services` - ігрова логіка (`GameService`), незалежна від HTTP та бази даних.
- `resources/views` - Blade-шаблони.
- `routes/web.php` - маршрути застосунку.
- `database/migrations` - міграції Laravel (`registrations`, `game_results`), застосовуються через `php artisan migrate`.

## Архітектурні рішення

У проєкті свідомо немає `ServiceProvider` з біндингами, інтерфейсів для сервісів і repository-шару з інтерфейсами поверх Eloquent - відповідно до принципу YAGNI з ТЗ:

- `GameService` і моделі (`Registration`, `GameResult`) існують в одній реалізації.
- Тестів з моками немає, отже інтерфейси заради тестованості нічого не дають.
- Eloquent-моделі вже є достатньою абстракцією над БД - окремий repository-шар поверх них лише дублював би виклики `Model::query()`.
