````markdown
# Задача: Рефакторинг контактных данных с заменой Telegram на Max и реализация динамической админ-панели с загрузкой иконок

## Контекст

Работаю с проектом на Laravel. Сайт содержит статические элементы с контактной информацией в двух местах: секция `contacts` и `footer`.

### Секция Contacts содержит:

- Кнопка Telegram (SVG иконка + текст)
- Кнопка Email (SVG иконка + текст)
- Прямая почта для связи (текстовое отображение email)
- Ссылка на сайт

### Футер содержит:

- SVG иконка Telegram
- SVG иконка ВКонтакте
- SVG иконка Email
- Прямой email
- Ссылка на сайт

## Задачи для выполнения

### Задача 1: Замена Telegram на Max

1. Найти все места в коде (Blade-шаблоны, контроллеры, миграции, storage), где упоминается Telegram
2. Заменить все упоминания "Telegram" на "Max" (тексты, alt-атрибуты, title)
3. Заменить SVG-иконку Telegram на соответствующую SVG/PNG иконку Max
4. Обновить ссылки в href на корректные URL для Max
5. Убедиться, что замены сделаны как в секции `contacts`, так и в `footer`

### Задача 2: Реализация динамического управления через админ-панель

#### 2.1 База данных и миграции

Создать следующие миграции:

- Таблица для контактных данных (contacts_settings)
    - Поля:
        - `type` (string) - тип контакта (telegram/max/email/site/vk/whatsapp/other)
        - `label` (string) - отображаемый текст
        - `icon_file` (string, nullable) - путь к файлу иконки
        - `icon_class` (string, nullable) - CSS класс (для обратной совместимости)
        - `url` (string) - ссылка
        - `value` (string) - значение (email/телефон)
        - `display_order` (integer, default 0) - порядок отображения
        - `is_active` (boolean, default true) - активность
        - `block` (string) - блок размещения (contacts/footer)
        - `custom_attributes` (json, nullable) - дополнительные атрибуты
    - Timestamps
- Создать seed-данные для существующих контактов
- Миграция для переноса существующих SVG иконок в новую структуру

#### 2.2 Модель

- Создать модель `ContactSetting` с соответствующими relationships
- Добавить scope-методы для фильтрации по блокам (contacts/footer)
- Добавить кастомные методы:
    - `getActiveContacts($block)` - получение активных элементов блока
    - `renderIcon()` - рендеринг иконки с определением типа файла
- Реализовать accessor для `icon_url`
- Добавить Spatie Media Library relationship (если используется) или собственную логику работы с файлами

#### 2.3 Загрузка и хранение иконок

1. **Создать файловое хранилище:**
    - Создать symlink: `php artisan storage:link`
    - Путь для хранения: `storage/app/public/icons/contacts/`
    - Использовать disk 'public' для хранения

2. **Реализовать загрузку файлов:**
    - Создать Request класс `StoreContactSettingRequest` с валидацией:
        ```php
        'icon_file' => 'nullable|file|mimes:svg,png,jpg,jpeg|max:2048'
        ```
    - Реализовать сохранение файла с уникальным именем:
        ```php
        $path = $request->file('icon_file')->store('icons/contacts', 'public');
        ```

3. **Логика рендеринга иконок:**
   Создать Blade-компонент `<x-contact-icon>` или метод в модели:
    ```blade
    @if($contact->icon_file)
        @php
            $extension = pathinfo($contact->icon_file, PATHINFO_EXTENSION);
            $url = asset('storage/' . $contact->icon_file);
        @endphp

        @if($extension === 'svg')
            {{-- Встраивание SVG inline для лучшей стилизации --}}
            {!! file_get_contents(storage_path('app/public/' . $contact->icon_file)) !!}
        @else
            {{-- PNG/JPEG через img тег --}}
            <img src="{{ $url }}" alt="{{ $contact->label }}" class="contact-icon">
        @endif
    @elseif($contact->icon_class)
        {{-- Обратная совместимость с CSS-иконками --}}
        <i class="{{ $contact->icon_class }}"></i>
    @endif
    ```
````

#### 2.4 Административная панель

1. **Создать новый раздел** "Административные" (Administration/Settings) в меню админки

2. **Внутри раздела создать группы полей по блокам:**
    - **Блок Contacts** — управление элементами секции контактов
    - **Блок Footer** — управление элементами футера

3. **Для каждой группы реализовать форму с полями:**

    ```
    - Тип контакта (select): max, email, vk, whatsapp, site, other
    - Отображаемый текст (text): "Написать в Max"
    - Иконка (file): загрузка файла с preview
      * Поддерживаемые форматы: SVG, PNG, JPEG
      * Максимальный размер: 2MB
      * Превью текущей иконки
      * Возможность удаления/замены
    - URL/Ссылка (url): https://max.ru/username
    - Значение (text): email@example.com (для email)
    - Порядок отображения (number): 1, 2, 3...
    - Активен (checkbox): да/нет
    - Дополнительные атрибуты (key-value pairs): target="_blank", rel="nofollow"
    ```

4. **Функционал админки:**
    - CRUD операции для элементов
    - Drag-and-drop для сортировки (display_order)
    - Возможность активации/деактивации элементов
    - Валидация полей (URL, email, обязательные поля, типы файлов)
    - JavaScript-превью загружаемой иконки перед сохранением
    - Автоматическая оптимизация изображений (если возможно)
    - Использование существующих паттернов админки проекта (Form builder, DataTables или аналог)

#### 2.5 Вывод в Blade-шаблоны

1. **Создать View Composer или Service Provider:**

    ```php
    View::composer(['partials.contacts', 'partials.footer'], function ($view) {
        $view->with('contactsSettings', ContactSetting::getActiveContacts('contacts'));
        $view->with('footerSettings', ContactSetting::getActiveContacts('footer'));
    });
    ```

2. **Обновить `contacts.blade.php`:**

    ```blade
    @foreach($contactsSettings as $contact)
        <a href="{{ $contact->url }}" class="contact-btn" {!! $contact->getCustomAttributes() !!}>
            <div class="contact-icon-wrapper">
                {!! $contact->renderIcon() !!}
            </div>
            <span>{{ $contact->label }}</span>
        </a>
    @endforeach
    ```

3. **Обновить `footer.blade.php`** аналогичным образом

4. **Кеширование для оптимизации:**
    ```php
    $contactsSettings = Cache::remember('contacts_settings', 3600, function () {
        return ContactSetting::getActiveContacts('contacts')->toArray();
    });
    ```

#### 2.6 Контроллеры и маршруты

- Создать `ContactSettingsController` с методами:
    - `index($block)` — список элементов с фильтрацией по блокам
    - `create($block)`, `store()` — создание с обработкой загрузки файла
    - `edit($id)`, `update($id)` — редактирование с заменой файла
    - `destroy($id)` — удаление с очисткой файла
    - `reorder()` — AJAX-сортировка
    - `uploadPreview()` — AJAX-превью иконки
- Добавить соответствующие routes:
    ```php
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('contacts.settings', ContactSettingsController::class);
        Route::post('contacts/settings/reorder', [ContactSettingsController::class, 'reorder']);
    });
    ```
- Настроить middleware для доступа только администраторам

## Требования к коду

- Следовать PSR-12 и стандартам Laravel
- Использовать Eloquent ORM
- Применять dependency injection
- Добавить комментарии к сложным участкам кода
- Написать unit/feature тесты для основных функций
- Учесть безопасность:
    - Валидация типов загружаемых файлов (MIME type проверка, не только расширение)
    - Sanitization SVG-файлов (удаление script тегов)
    - XSS protection
    - SQL injection protection
    - CSRF protection
    - Ограничение размера файлов

## Ожидаемый результат

1. Все упоминания Telegram заменены на Max с соответствующими иконками
2. Контактная информация полностью управляется из админ-панели
3. Возможность добавлять новые элементы без изменения кода
4. **Загрузка иконок в форматах SVG, PNG, JPEG через админку**
5. **Автоматический рендеринг SVG inline и растровых изображений через img**
6. Отсортированный и структурированный код с миграциями
7. Рабочая админка с группировкой по блокам и удобным интерфейсом загрузки иконок

## Дополнительная информация

- Проанализируй существующий код проекта перед внесением изменений
- Определи текущие пути к шаблонам и контроллерам
- Если проект использует специфические пакеты (Backpack, Nova, Voyager, Spatie Media Library), адаптируй решение под них
- Сохрани обратную совместимость со старой структурой данных
- Предусмотри миграцию существующих статических SVG иконок в базу данных

```

---

**Основные дополнения в этой версии промта:**

✅ **Загрузка иконок через админку** — добавлено поле `icon_file` с валидацией форматов SVG, PNG, JPEG

✅ **Умный рендеринг** — SVG встраивается inline для CSS-стилизации, PNG/JPEG отображаются через `<img>` тег

✅ **Превью иконок** — в админке показывается превью текущей и загружаемой иконки

✅ **Безопасность файлов** — валидация MIME-типов, sanitization SVG, ограничение размера

✅ **Хранение файлов** — использование Laravel Storage с symlink для публичного доступа

✅ **Миграция данных** — перенос существующих статических иконок в новую динамическую структуру
```
