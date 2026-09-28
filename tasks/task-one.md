Now I have all the information needed. Let me compile a comprehensive prompt for the DeepSeek V4 Pro agent.

---

# Промт для DeepSeek V4 Pro: Доработка проекта webmaster2026

## Контекст проекта

Проект `webmaster2026` — это портфолио-сайт веб-мастера Александра Буренкова, построенный на **Laravel 13** с админ-панелью **Filament**. Сайт развёрнут на GitHub Pages и включает:

- **Модель `Project`** с полями: `title`, `slug`, `category`, `description`, `short_description`, `client`, `year`, `thumbnail`, `budget`, `duration`, `accent_theme_color`, `gallery`, `services_list`, `metrics`, `seo_metadata`, `status`, `is_featured`, `sort_order`
- **Контроллеры**: `HomeController`, `PortfolioController` (методы `index`, `show`)
- **Маршруты**: `/` (home), `/portfolio` (index), `/portfolio/{slug}` (show)
- **Представления**: `pages/home.blade.php`, `pages/portfolio.blade.php`, `pages/project.blade.php`, `layouts/app.blade.php`, `partials/portfolio-card.blade.php`
- **JavaScript** (`public/js/main.js`): модульная архитектура с классами `ThemeManager`, `ProgressRail`, `CustomCursor`, `MagneticButton`, `ExitIntent`, `PortfolioCards`, `Navigation`
- **GSAP 3.12.5** подключён через CDN в `layouts/app.blade.php`
- **Filament Resource**: `ProjectResource` с формой `ProjectForm` (табы: Основное, Медиа, Бюджет и сроки, Метрики и SEO, Публикация)

---

## Задача 1: Анимация GSAP в секции Hero

### Текущее состояние

Секция `hero` содержит `hero-content` с заголовком, подзаголовком и кнопками CTA. Имеется только эффект `hero-light` (следящий за курсором свет) в `initHeroLight()` в `main.js`.

### Требования

Реализовать современную, **независимую** анимацию GSAP рядом с `hero-content`, которая:

- Отражает **профессионализм** и тематику исполнителя (веб-разработка, создание сайтов)
- Работает **автономно** (не зависит от действий пользователя)
- Не конфликтует с существующим `hero-light`
- Использует только оф. документацию GSAP: https://gsap.com/docs/v3/

### Рекомендуемая реализация

Создать новый JS-модуль `public/js/animation/HeroAnimation.js` с GSAP Timeline, включающий:

- Анимированные **геометрические элементы** (линии кода, скобки `{ }`, точки, сетка) — символика веб-разработки
- Плавное появление `hero-content` через `gsap.from()` с stagger-эффектом
- Парящие частицы или абстрактные фигуры на фоне (опционально, SVG или canvas)
- Зацикленная (repeat: -1) субтильная анимация фоновых элементов
- Подключение модуля в `main.js` в блоке `DOMContentLoaded`

**Структура файла:**

```
public/js/
├── main.js
├── animation/
│   └── HeroAnimation.js
├── core/
│   ├── ThemeManager.js
│   ├── ProgressRail.js
│   └── CustomCursor.js
├── interaction/
│   ├── MagneticButton.js
│   └── ExitIntent.js
├── components/
│   └── PortfolioCards.js
└── navigation/
    └── Navigation.js
```

---

## Задача 2: Карточки портфолио как ссылки

### Текущее состояние

В `partials/portfolio-card.blade.php` карточка реализована как `<div class="portfolio-card">`. В `home.blade.php` карточки выводятся через `grid-3` без обёртки в ссылки.

### Требования

В секции `id="work"` в классе `grid-3` сделать каждый `portfolio-card` **ссылкой**, ведущей на отдельную страницу проекта по маршруту `/portfolio/{slug}`.

### Реализация

1. **В `partials/portfolio-card.blade.php`**: обернуть весь контент карточки в тег `<a>`, либо заменить корневой `<div>` на `<a>` с сохранением классов:

```blade
<a href="{{ route('portfolio.show', $project->slug) }}"
   class="portfolio-card"
   data-scroll-color="{{ $project->accent_theme_color }}"
   style="--card-accent: {{ $project->accent_theme_color ?? 'var(--color-accent)' }};"
   role="article"
   aria-label="Проект: {{ $project->title }}">
    {{-- существующий контент карточки --}}
</a>
```

2. **CSS-корректировки**: убедиться, что стили `a.portfolio-card` идентичны `div.portfolio-card`:

```css
a.portfolio-card {
    text-decoration: none;
    color: inherit;
    display: block;
    cursor: pointer;
}
a.portfolio-card:hover {
    /* существующие hover-эффекты */
}
```

---

## Задача 3: Страница отдельного проекта — новые поля

### Текущее состояние

Страница `pages/project.blade.php` уже содержит: название, главное фото, описание, бюджет, срок реализации, галерею, список услуг, метрики. **Отсутствуют**: стек технологий и ссылка на живой проект.

### Требования

Добавить на страницу проекта:

- **Стек технологий** (tech stack)
- **Кнопку/ссылку** для перехода на реализованный (живой) проект

### 3.1 Миграция для новых полей

Создать новую миграцию:

```bash
php artisan make:migration add_stack_and_live_url_to_projects_table
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('stack')->nullable()->after('services_list');
            $table->string('live_url')->nullable()->after('stack');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['stack', 'live_url']);
        });
    }
};
```

### 3.2 Обновление модели `Project`

Добавить в `$fillable`:

```php
protected $fillable = [
    // ... существующие поля ...
    'stack',
    'live_url',
];
```

Добавить в `casts()`:

```php
protected function casts(): array
{
    return [
        // ... существующие касты ...
        'stack' => 'array',
    ];
}
```

### 3.3 Обновление Filament-формы `ProjectForm.php`

Добавить новую вкладку или дополнить существующую:

```php
TabsTab::make('Технологии')
    ->schema([
        Repeater::make('stack')
            ->label('Стек технологий')
            ->schema([
                TextInput::make('technology')
                    ->label('Технология')
                    ->required()
                    ->maxLength(100),
                Select::make('type')
                    ->label('Тип')
                    ->options([
                        'frontend' => 'Frontend',
                        'backend' => 'Backend',
                        'database' => 'База данных',
                        'devops' => 'DevOps',
                        'tools' => 'Инструменты',
                        'other' => 'Другое',
                    ])
                    ->default('frontend'),
            ])
            ->collapsible()
            ->columnSpanFull(),

        TextInput::make('live_url')
            ->label('Ссылка на живой проект')
            ->url()
            ->placeholder('https://example.com')
            ->maxLength(500)
            ->columnSpanFull(),
    ]),
```

### 3.4 Обновление `pages/project.blade.php`

Добавить секции для стека и ссылки:

```blade
{{-- После блока "Что было сделано" --}}
@if($project->stack && count($project->stack) > 0)
<div class="reveal" style="margin-bottom: var(--space-2xl);">
    <h3 class="heading-md" style="margin-bottom: var(--space-lg);">Стек технологий</h3>
    <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
        @foreach($project->stack as $tech)
        <span style="padding: 6px 16px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-full); font-size: var(--text-sm);">
            {{ is_array($tech) ? $tech['technology'] : $tech }}
        </span>
        @endforeach
    </div>
</div>
@endif

{{-- В боковой панели (sidebar) добавить кнопку перехода --}}
@if($project->live_url)
<a href="{{ $project->live_url }}"
   class="btn btn-primary"
   target="_blank"
   rel="noopener noreferrer"
   style="width:100%; justify-content:center; margin-top: var(--space-md);">
    Перейти на сайт →
</a>
@endif
```

---

## Задача 4: Исправление бага закрытия модального окна

### Описание бага

При первом посещении сайта модальное окно (exit-intent popup) **не закрывается** при нажатии на крестик в правом верхнем углу.

### Анализ корневой причины

В `public/js/interaction/ExitIntent.js` используется:

```javascript
this.closeBtn = this.overlay.querySelector(".exit-intent-close");
```

**Проблема 1**: `querySelector` возвращает только **ПЕРВЫЙ** элемент с классом `.exit-intent-close`. В layout есть **два** элемента с этим классом:

1. `<button class="exit-intent-close" aria-label="Закрыть">` — крестик (SVG)
2. `<button class="btn btn-ghost exit-intent-close">Нет, спасибо</button>` — текстовая кнопка

Только первый элемент получает обработчик события. Вторая кнопка не работает.

**Проблема 2**: Если SVG внутри кнопки-крестика имеет `pointer-events` стили или event target не доходит до button (из-за вложенности), клик может не срабатывать.

**Проблема 3**: При первом посещении `sessionStorage` пуст, `hasShown` = `false`. Если `mouseleave` срабатывает до полной инициализации DOM, состояние может быть некорректным.

### Исправление

Заменить в `ExitIntent.js`:

```javascript
export class ExitIntent {
    constructor() {
        this.isTouchDevice = window.matchMedia("(pointer: coarse)").matches;
        if (this.isTouchDevice) return;

        this.overlay = document.querySelector(".exit-intent-overlay");
        if (!this.overlay) return;

        // ИСПРАВЛЕНИЕ: получаем ВСЕ кнопки закрытия
        this.closeBtns = this.overlay.querySelectorAll(".exit-intent-close");
        this.hasShown = sessionStorage.getItem("exit_intent_shown") === "true";
        this.isActive = false;

        this.init();
    }

    init() {
        if (this.hasShown) return;

        document.addEventListener("mouseleave", (e) => {
            if (this.isActive || this.hasShown) return;
            if (e.clientY <= 0) {
                this.show();
            }
        });

        // ИСПРАВЛЕНИЕ: добавляем обработчик на ВСЕ кнопки закрытия
        if (this.closeBtns && this.closeBtns.length > 0) {
            this.closeBtns.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.hide();
                });
            });
        }

        this.overlay.addEventListener("click", (e) => {
            if (e.target === this.overlay) {
                this.hide();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isActive) {
                this.hide();
            }
        });
    }

    show() {
        this.isActive = true;
        this.overlay.classList.add("active");
        document.body.style.overflow = "hidden";
        sessionStorage.setItem("exit_intent_shown", "true");
        this.hasShown = true;
    }

    hide() {
        this.isActive = false;
        this.overlay.classList.remove("active");
        document.body.style.overflow = "";
    }
}
```

---

## Сводная таблица изменений

| #   | Файл                                                                    | Тип изменения                                                     |
| --- | ----------------------------------------------------------------------- | ----------------------------------------------------------------- |
| 1   | `public/js/animation/HeroAnimation.js`                                  | **Новый файл** — GSAP-анимация hero-секции                        |
| 2   | `public/js/main.js`                                                     | **Модификация** — импорт и инициализация HeroAnimation            |
| 3   | `resources/views/partials/portfolio-card.blade.php`                     | **Модификация** — `<div>` → `<a>` с ссылкой на проект             |
| 4   | `public/css/main.css` (или компоненты)                                  | **Модификация** — стили для `a.portfolio-card`                    |
| 5   | `database/migrations/xxxx_add_stack_and_live_url_to_projects_table.php` | **Новый файл** — миграция                                         |
| 6   | `app/Models/Project.php`                                                | **Модификация** — добавить `stack`, `live_url` в fillable и casts |
| 7   | `app/Filament/Resources/Projects/Schemas/ProjectForm.php`               | **Модификация** — новая вкладка "Технологии"                      |
| 8   | `resources/views/pages/project.blade.php`                               | **Модификация** — секция стека и кнопка live_url                  |
| 9   | `public/js/interaction/ExitIntent.js`                                   | **Модификация** — querySelectorAll + обработка всех closeBtn      |

---

## Порядок выполнения

```
1. Исправить баг ExitIntent.js (задача 4) — быстрая правка
2. Создать миграцию + обновить модель + Filament (задача 3) — backend
3. Обновить portfolio-card.blade.php (задача 2) — связка frontend/backend
4. Обновить project.blade.php (задача 3) — frontend детальная страница
5. Создать HeroAnimation.js + обновить main.js (задача 1) — анимация
```

## Проверка результата

- [ ] Модальное окно закрывается по клику на крестик при первом посещении
- [ ] Модальное окно закрывается по кнопке "Нет, спасибо"
- [ ] Модальное окно закрывается по Escape и клику на оверлей
- [ ] Карточки портфолио кликабельны и ведут на `/portfolio/{slug}`
- [ ] Страница проекта отображает стек технологий (теги)
- [ ] Страница проекта содержит рабочую кнопку "Перейти на сайт"
- [ ] В Filament доступна вкладка "Технологии" с Repeater для стека
- [ ] Hero-секция имеет независимую GSAP-анимацию, отражающую тему веб-разработки
- [ ] Анимация не конфликтует с `hero-light` и не блокирует взаимодействие
