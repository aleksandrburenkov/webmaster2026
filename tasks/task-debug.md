# Промт для кодинг-агента

Bсправлениt ошибки `__PHP_Incomplete_Class` в Laravel-проекте.

---

```markdown
# Задача: Исправить ошибку `__PHP_Incomplete_Class` при чтении ContactSetting из сессии/кэша

## 🎭 Роль

Ты — опытный Senior Laravel Developer (10+ лет опыта). Ты отлично понимаешь жизненный цикл приложения Laravel, работу сессий, кэша, автозагрузки классов и подводные камни сериализации Eloquent-моделей.

## 📋 Контекст проблемы

В проекте возникает критическая ошибка после перезапуска сервера/PHP-FPM:
```

The script tried to call a method on an incomplete object.
Please ensure that the class definition "Illuminate\Database\Eloquent\Collection"
of the object you are trying to operate on was loaded _before_ unserialize() gets called...

````

**Причина:** Eloquent-коллекция моделей `ContactSetting` (ключ `footerSettings`) сохраняется в сессию или кэш в виде сериализованных объектов. При старте приложения Laravel загружает сессию раньше, чем успевает отработать автозагрузчик для классов `Illuminate\Database\Eloquent\Collection` и `App\Models\ContactSetting`, из-за чего PHP возвращает `__PHP_Incomplete_Class`.

## 🎯 Цель
Рефакторить код так, чтобы в сессию/кэш сохранялись **только скалярные данные (массивы)**, а не Eloquent-модели и коллекции. Это гарантированно решит проблему навсегда.

## 🔧 План действий

### Шаг 1. Поиск проблемных мест
Найди в проекте все места, где в сессию или кэш сохраняется коллекция `ContactSetting` или другие Eloquent-модели. Используй grep/semantic search по следующим паттернам:
- `session(['footerSettings'` / `session()->put('footerSettings'`
- `cache(['footerSettings'` / `Cache::put('footerSettings'`
- `ContactSetting::all()` в контексте сохранения в session/cache
- Другие модели, сохраняемые аналогично (проверь весь проект глобально)

### Шаг 2. Рефакторинг сохранения (Write)
Замени прямое сохранение моделей на сохранение простого массива через `->toArray()`:

**Было (удалить):**
```php
session(['footerSettings' => $settings]);
// или
cache(['footerSettings' => $settings], 3600);
````

**Стало (написать):**

```php
// Если $settings — это Collection:
$settingsArray = $settings->toArray();
session(['footerSettings' => $settingsArray]);
// или для кэша:
cache(['footerSettings' => $settingsArray], 3600);
```

> ⚠️ Важно: Если в моделях есть загруженные отношения (`->with('relation')`), `toArray()` корректно преобразует и их. Проверь, какие поля используются в шаблонах.

### Шаг 3. Рефакторинг чтения (Read) и Blade-шаблонов

Найди все места чтения из сессии/кэша и обновлённые Blade-шаблоны.

**Вариант А — Работа с массивами (рекомендуется, самый надёжный):**
Обнови Blade-шаблоны, где происходит обращение к данным:

```blade
{{-- Было --}}
@foreach($footerSettings as $contact)
    {{ $contact->url }}
    {{ $contact->icon }}
@endforeach

{{-- Стало --}}
@foreach($footerSettings as $contact)
    {{ $contact['url'] }}
    {{ $contact['icon'] }}
@endforeach
```

**Вариант Б (если в шаблонах много логики и методов моделей):**
При чтении из кэша/сессии гидратируй массив обратно в объекты через `Hydrator` или маппинг:

```php
$settingsArray = session('footerSettings', []);
$settings = collect($settingsArray)->map(fn($item) => (new ContactSetting())->forceFill($item));
```

> Используй этот вариант ТОЛЬКО если в шаблонах вызываются методы моделей (`$contact->formattedPhone()` и т.п.), иначе используй Вариант А.

### Шаг 4. Проверка Middleware

Открой `app/Http/Kernel.php` и убедись, что middleware, работающий с сессией, не пытается загружать Eloquent-модели слишком рано. Если найдёшь — вынеси логику загрузки в сервис-провайдер (`AppServiceProvider::boot()`) или используй `view()->share()` после загрузки классов.

## ✅ Критерии приёмки (Definition of Done)

- [ ] В проекте **нигде** не сохраняются Eloquent-модели или `Collection` напрямую в `session()` / `Cache::put()`
- [ ] Все ключи (`footerSettings` и аналогичные) сохраняются как массивы через `->toArray()`
- [ ] Blade-шаблоны обновлены: обращения `$model->property` заменены на `$array['property']` (если выбран Вариант А)
- [ ] Нет обращений к методам/связям несуществующих классов на старте приложения
- [ ] Добавлен PHPDoc к местам сохранения, чтобы другие разработчики не повторяли ошибку (пример комментария: `// ВАЖНО: сохраняем только toArray(), иначе возникнет __PHP_Incomplete_Class`)

## ⚠️ Дополнительные требования

- Не трогай логику получения данных из БД — только слой сериализации/десериализации
- Если найдёшь кэширование в Redis/Memcached — примени те же правила (эти драйверы тоже сериализуют объекты)
- После рефакторинга предложи команду для очистки кэша/сессий (`php artisan cache:clear` и миграция для старых сессий при необходимости)
- Покажи diff-список всех изменённых файлов с кратким объяснением изменений

````

---

## 💡 Как использовать этот промт

3. **После выполнения агентом:** Обязательно выполните очистку старого кэша/сессий, иначе на проде могут остаться «отравленные» сериализованные объекты со старыми классами:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   # Для сессий — при необходимости сбросить через миграцию или удалить файлы в storage/framework/sessions
````
