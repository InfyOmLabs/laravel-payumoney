<h1 align="center"><img src="https://assets.infyom.com/open-source/infyom-logo.png" alt="InfyOm"></h1>

PayuMoney Integration with Laravel
==========================

[![Total Downloads](https://poser.pugx.org/infyomlabs/laravel-payumoney/downloads)](https://packagist.org/packages/infyomlabs/laravel-payumoney)
[![Monthly Downloads](https://poser.pugx.org/infyomlabs/laravel-payumoney/d/monthly)](https://packagist.org/packages/infyomlabs/laravel-payumoney)
[![Daily Downloads](https://poser.pugx.org/infyomlabs/laravel-payumoney/d/daily)](https://packagist.org/packages/infyomlabs/laravel-payumoney)
[![License](https://poser.pugx.org/infyomlabs/laravel-payumoney/license)](https://packagist.org/packages/infyomlabs/laravel-payumoney)

Easy to use integration for [PayUMoney](https://www.payu.in/) into Laravel apps.

## Video Tutorial

[![Watch the video](http://assets.infyom.com/open-source/laravel-payumoney-sample.gif)](http://assets.infyom.com/open-source/laravel-payumoney-sample.gif)


## Usage

```
composer require infyomlabs/laravel-payumoney:"^1.3.0"
```

Publish the config files and view files

```
php artisan laravel-payumoney:publish
```

Above command will publish the following files:

- resources/views/payumoney/pay.blade.php
- resources/views/payumoney/fail.blade.php
- config/payu.php
- App\Http\Controllers\PayuMoneyController.php


Add the following two routes to `VerifyCsrfToken.php` into `except` as its callback routes.

```
payu-money-payment-cancel
payu-money-payment-success
```

You need to change the `PAYU_TEST_MODE=false` when you are done with testing and want to use it in production.

Route to open payumoney form :

```
{APP_URL}/payu-money-payment
```





