# Admin LTE3 Control Panel for Laravel Framework

[![License](https://img.shields.io/packagist/l/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Build Status](https://img.shields.io/github/stars/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://github.com/fomvasss/laravel-lte3)
[![Latest Stable Version](https://img.shields.io/packagist/v/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Total Downloads](https://img.shields.io/packagist/dt/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://packagist.org/packages/fomvasss/laravel-lte3)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-lte3.svg?style=for-the-badge)](https://scrutinizer-ci.com/g/fomvasss/laravel-lte3)

Create easily and quickly a convenient and functional dashboard for web-site, blogs, shops, crm, apps with the help of a template and a powerful system for building fields and forms.

![screenshot](public/img/screen.gif)

----------

## Installation

Run:

```bash
composer require fomvasss/laravel-lte3

php artisan lte3:publish --tag=lte3-config

php artisan lte3:install
```

That's all. You can usage LTE3 in your project :)

All examples the fields & form components are in the `.../examples/components.vlade.php`

After installation, you can see it: `http://site.test/lte3/exsmples`


## Configuration

Configuration file: `config/lte3.php`

For correct work navigation in dashboard, apply middleware. Add this to `App\Http\Kernel.php`:

```
$middlewareGroups = [
  'web' => [
    //...
    \Fomvasss\ItsLte\Http\Middleware\LteRequestOptions::class,
  ],
];
```

## Publishing (optional)

Of course, you can published partial for customize:

- views:
`lte-view-components`, `lte-view-examples`, `lte-view-auth`, `lte-view-parts`, `lte-view-layouts`

- other:
`lte-config`, `lte-assets`, `lte-lang`

```bash
php artisan lte3:publish --tag=lte-view-components
```
## Structure

- `config/lte3.php` - package config
- `public/vendor/adminlte` - original AdminLte assets ([ColorlibHQ/AdminLTE2](https://adminlte.io/themes/AdminLTE/))
- `public/vendor/lte3` - custom assets (you can change this)
- `resources/views/vendor/lte3` - options publishing
  - `auth`
  - `layouts`
  - `parts`
  - `components`
  - `examples`
  
## Usage & Development

See [examples.blade.php](https://github.com/fomvasss/laravel-lte3/blob/master/resources/views/examples/components.blade.php)

## Recommended

- For file manage use [laravel-medialibrary-extension](https://github.com/fomvasss/laravel-medialibrary-extension)
- For manage taxonomy use [laravel-simple-taxonomy](https://github.com/fomvasss/laravel-simple-taxonomy)
- For save vars, configs use [laravel-variables](https://github.com/fomvasss/laravel-variables)
- Text Editor: [CKEditor](https://github.com/UniSharp/laravel-ckeditor)
- File manager: [LFM](https://github.com/UniSharp/laravel-filemanager):


## Credits
- [ColorlibHQ/AdminLTE2](https://adminlte.io/themes/AdminLTE/)
- [ColorlibHQ/AdminLTE3](https://adminlte.io/themes/v3/)
- [fomvasss/laravel-lte3](https://github.com/fomvasss/laravel-lte3)
- [web-west/itslte](https://github.com/web-west/itslte)
- [laravelcollective](https://laravelcollective.com/docs/6.x/html)
