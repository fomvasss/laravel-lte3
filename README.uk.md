# Laravel LTE3

[![License](https://img.shields.io/packagist/l/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Latest Stable Version](https://img.shields.io/packagist/v/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)

Адмінка для Laravel на [AdminLTE 3](https://adminlte.io/themes/v3/): готовий layout з навбаром, сайдбаром, алертами й сторінками авторизації та Blade-конструктор форм майже з сорока компонентів — select2 з AJAX-пошуком, дейтпікери, файлові менеджери, завантаження в Spatie Media Library, дерева, інлайн-редагування.

[English](README.md) · Документація (англійською): **https://fomvasss.github.io/laravel-lte3/**

![Components](public/img/screen.gif)

- **Поле одним рядком** — `{!! Lte3::text('title') !!}` малює form group з лейблом, значенням, помилкою валідації й підказкою
- **Значення звідусіль** — old input, запит, явне значення, модель форми або default
- **AJAX з коробки** — автозбереження, AJAX-пошук і теги, деревоподібні селекти, модалки з контентом із сервера
- **Файли** — звичайне завантаження, Laravel File Manager з drag and drop, поле Media Library з превʼю, сортуванням і властивостями
- **Динамічний контент** — поля в модалках, повторювачах блоків і AJAX-відповідях ініціалізуються самі
- **Layout** — світла / темна / системна тема, фільтри, заголовок сторінки з хлібними крихтами, алерти toastr / sweetalert / Bootstrap

## Вимоги

- PHP ^8.0
- Laravel 9 – 13
- `almasaeed2010/adminlte` ^3.2

## Встановлення

```bash
composer require fomvasss/laravel-lte3
composer require almasaeed2010/adminlte
php artisan lte3:install
```

Додай `\Fomvasss\Lte3\Http\Middleware\LteRequestOptions` до маршрутів адмінки. Подробиці, асети через симлінки й демо-сторінки на `/lte3` — [Installation](https://fomvasss.github.io/laravel-lte3/installation/).

## Ліцензія

MIT. Див. [LICENSE.md](LICENSE.md).
