# Задача для автономного ИИ-агента: режим обслуживания в Laravel + Filament

## Цель

Реализовать в админ-панели на Laravel + Filament функционал включения и отключения режима обслуживания сайта с помощью переключателя `Toggle`.

При включённом режиме обслуживания:

- публичная часть сайта должна показывать страницу обслуживания;
- ответ должен иметь HTTP-статус `503 Service Unavailable`;
- админ-панель должна оставаться доступной;
- администратор должен иметь возможность выключить режим обслуживания.

---

## Обязательные требования

1. Проект использует **Laravel + Filament**.
2. Переключатель должен находиться в уже существующем блоке админ-панели с названием:
    - `Администрирование`
    - либо в похожем существующем разделе, если он используется для администрирования.
3. Если в этом блоке уже есть страница настроек — добавить переключатель туда.
4. Если страницы настроек нет — создать отдельную страницу в этом блоке.
5. Не использовать `php artisan down` как основной механизм.
6. Режим обслуживания должен храниться в базе данных.
7. Если в проекте уже есть механизм настроек, использовать его.
8. Если механизма настроек нет, создать простую таблицу `settings`.
9. Страница обслуживания должна быть простой и автономной.
10. Все пользовательские тексты должны быть на русском языке.
11. Нельзя блокировать доступ к админ-панели при включённом режиме обслуживания.
12. Необходимо сохранить существующий стиль кода проекта.

---

## Что должно получиться

После выполнения задачи:

- [ ] В админ-панели в разделе `Администрирование` есть переключатель режима обслуживания.
- [ ] Переключатель сохраняет состояние.
- [ ] При включённом режиме публичный сайт возвращает страницу обслуживания.
- [ ] Страница обслуживания возвращает статус `503`.
- [ ] Админ-панель продолжает работать.
- [ ] Страница входа в админку продолжает работать.
- [ ] Нет ошибок из-за отсутствия таблицы настроек.
- [ ] Существующий функционал не сломан.
- [ ] При наличии тестов добавлены базовые тесты.

---

# Алгоритм выполнения

## 1. Исследовать проект

Перед изменением кода определить:

- версию Laravel;
- версию Filament;
- путь к админ-панели;
- существующие навигационные группы;
- есть ли уже раздел `Администрирование`;
- есть ли уже страница настроек;
- есть ли таблица `settings`;
- есть ли модель настроек;
- есть ли установлен `spatie/laravel-settings` или другой механизм настроек;
- есть ли тесты.

Если уже есть готовый механизм настроек — использовать его.

---

## 2. Хранилище настроек

Если в проекте нет механизма настроек, создать миграцию для таблицы `settings`.

Таблица должна содержать:

```php
$table->id();
$table->string('key')->unique();
$table->text('value')->nullable();
$table->timestamps();
```

Ключ настройки:

```text
maintenance_mode
```

Значения:

```text
1 — режим обслуживания включён
0 — режим обслуживания выключен
```

---

## 3. Модель настройки

Если модель настроек отсутствует, создать:

Если модель уже есть — использовать существующую.

---

## 4. Сервис режима обслуживания

Создать сервис:

```text
app/Services/MaintenanceModeService.php
```

Если проект использует другой механизм настроек, адаптировать сервис под него.

---

## 5. Middleware для проверки режима обслуживания

Создать middleware:

```text
app/Http/Middleware/CheckMaintenanceMode.php
```

Пример:

```php
<?php

namespace App\Http\Middleware;

use App\Services\MaintenanceModeService;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CheckMaintenanceMode
{
    public function __construct(
        protected MaintenanceModeService $maintenanceModeService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->maintenanceModeService->isEnabled()) {
            return $next($request);
        }

        if ($this->isExempt($request)) {
            return $next($request);
        }

        return response()
            ->view('maintenance', [], 503)
            ->header('Retry-After', '3600');
    }

    protected function isExempt(Request $request): bool
    {
        $path = trim($request->path(), '/');

        $technicalPaths = [
            'livewire',
            'file-upload',
            'storage',
            'build',
            'css',
            'js',
            'vendor',
            'favicon.ico',
            'up',
        ];

        foreach ($technicalPaths as $technicalPath) {
            if ($path === $technicalPath || str_starts_with($path, $technicalPath . '/')) {
                return true;
            }
        }

        try {
            $panels = collect(Filament::getPanels());

            $hasRootPanel = $panels->contains(
                fn ($panel) => trim((string) $panel->getPath(), '/') === ''
            );

            $panelPaths = $panels
                ->map(fn ($panel) => trim((string) $panel->getPath(), '/'))
                ->filter(fn ($panelPath) => $panelPath !== '')
                ->unique()
                ->values();
        } catch (Throwable $e) {
            $hasRootPanel = false;
            $panelPaths = collect();
        }

        if ($panelPaths->isEmpty()) {
            $panelPaths = collect([
                trim((string) config('filament.path', 'admin'), '/'),
            ]);
        }

        foreach ($panelPaths as $panelPath) {
            if ($path === $panelPath || str_starts_with($path, $panelPath . '/')) {
                return true;
            }
        }

        if ($hasRootPanel) {
            if (auth()->check()) {
                return true;
            }

            $authPaths = [
                'login',
                'logout',
                'password',
            ];

            foreach ($authPaths as $authPath) {
                if ($path === $authPath || str_starts_with($path, $authPath . '/')) {
                    return true;
                }
            }
        }

        return false;
    }
}
```

---

## 6. Регистрация middleware

### Laravel 11+

Файл:

```text
bootstrap/app.php
```

Добавить в `web`:

```php
use App\Http\Middleware\CheckMaintenanceMode;

$middleware->web(append: [
    CheckMaintenanceMode::class,
]);
```

### Laravel 10

Файл:

```text
app/Http/Kernel.php
```

Добавить в группу `web`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\CheckMaintenanceMode::class,
    ],
];
```

---

## 7. Страница обслуживания

Создать представление:

```text
resources/views/maintenance.blade.php
```

Проанализировать и использовать html и css стили согласно общей концепции проекта.

---

## 8. Страница или поле в Filament

Если подходящей страницы настроек нет, создать страницу:

```text
app/Filament/Pages/MaintenanceModePage.php
```

Создать представление страницы:

```text
resources/views/filament/pages/maintenance-mode.blade.php
```

Пример:

```blade
<x-filament-panels::page>
    <form class="space-y-6">
        {{ $this->form }}
    </form>
</x-filament-panels::page>
```

Если в разделе `Администрирование` уже есть страница настроек, добавить туда поле:

```php
Toggle::make('maintenance_mode')
    ->label('Режим обслуживания')
    ->helperText('Если включено, посетители сайта увидят страницу обслуживания.')
    ->live()
    ->afterStateUpdated(function ($state): void {
        app(\App\Services\MaintenanceModeService::class)->enable((bool) $state);
    }),
```

---

## 9. Права доступа

Если в проекте есть роли и права доступа, ограничить доступ к управлению режимом обслуживания.

Если отдельные права уже существуют — использовать их.

Если прав нет, разрешить доступ только авторизованным пользователям админ-панели.

---

## 10. Тесты

Если в проекте есть тесты, добавить или обновить их.

Проверить:

1. При выключенном режиме публичный сайт доступен.
2. При включённом режиме публичный сайт возвращает `503`.
3. При включённом режиме админ-панель не возвращает `503`.
4. Переключатель сохраняет состояние в базе данных.
5. После выключения режима сайт снова доступен.

---

# Критерии приёмки

Задача считается выполненной, если:

- [ ] В админ-панели есть переключатель режима обслуживания.
- [ ] Переключатель находится в блоке `Администрирование`.
- [ ] Состояние сохраняется в базе данных.
- [ ] При включённом режиме публичный сайт показывает страницу обслуживания.
- [ ] Страница обслуживания возвращает статус `503`.
- [ ] Админ-панель не блокируется.
- [ ] Страница входа в админ-панель не блокируется.
- [ ] Тексты на странице обслуживания на русском языке.
- [ ] Код не ломает существующий функционал.
- [ ] Выполнены команды:

```bash
php artisan migrate
php artisan route:list
php artisan test
```

Если тесты в проекте отсутствуют, как минимум проверить поведение вручную или через встроенные средства проекта.
