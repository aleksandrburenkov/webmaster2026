<!DOCTYPE html>
<html lang="ru" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Сайт на техническом обслуживании — Webmaster32</title>

    <link rel="stylesheet" href="/css/main.css?v={{ filemtime(public_path('css/main.css')) }}">

    <style>
        .maintenance {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--color-bg);
            overflow: hidden;
        }

        .maintenance::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, rgba(45, 45, 45, 0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(45, 45, 45, 0.06) 1px, transparent 1px);
            background-size: 80px 80px;
            -webkit-mask: radial-gradient(circle at 50% 50%, #fff, transparent 70%);
            mask: radial-gradient(circle at 50% 50%, #fff, transparent 70%);
            pointer-events: none;
        }

        .maintenance-inner {
            position: relative;

            margin: 0 auto;
            padding: var(--space-2xl) var(--space-lg);
            text-align: center;
        }

        .maintenance-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-lg);
            padding: 6px 16px;
            font-family: var(--font-mono);
            font-size: var(--text-xs);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--color-text-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            background: var(--color-surface);
        }

        .maintenance-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--color-text);
            animation: maintenance-pulse 2s ease-in-out infinite;
        }

        .maintenance-title {
            margin-bottom: var(--space-lg);
        }

        .maintenance-text {
            max-width: 46ch;
            margin: 0 auto var(--space-2xl);
        }

        .maintenance-actions {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-md);
            justify-content: center;
        }

        @keyframes maintenance-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.75);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .maintenance-badge::before {
                animation: none;
            }
        }
    </style>
</head>

<body>
    <main class="maintenance">
        <div class="container">
            <div class="maintenance-inner">
                <div class="maintenance-badge">Технические работы</div>

                <h1 class="display-md maintenance-title">Сайт скоро вернётся</h1>

                <p class="body-lg maintenance-text">
                    Мы проводим плановое обновление и совсем скоро снова будем онлайн.
                    Приносим извинения за временные неудобства и благодарим за понимание.
                </p>

                <div class="maintenance-actions">
                    <a href="mailto:admin@webmaster32.ru" class="btn btn-primary">Написать нам</a>
                    <a href="/" class="btn btn-secondary">Обновить страницу</a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
