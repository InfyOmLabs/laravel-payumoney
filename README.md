## PayuMoney Integration with Laravel

[![Watch the video](http://assets.infyom.com/open-source/laravel-payumoney-sample.gif)](http://assets.infyom.com/open-source/laravel-payumoney-sample.gif)


Installation steps :

```
composer require infyomlabs/laravel-payumoney:"^1.2.0"
```

Publish the config files and view files :

```
php artisan laravel-payumoney:publish
```

Note: Above command will publish following files

- resources/views/payumoney/pay.blade.php
- resources/views/payumoney/fail.blade.php
- config/payu.php
- App\Http\Controllers\PayuMoneyController.php


Add given two routes to `VerifyCsrfToken.php` into `except` as its callback routes.

```
payu-money-payment-cancel
payu-money-payment-success
```

You can change the `PAYU_TEST_MODE=false` when you are done with testing.

#### Route to open payumoney form :

```
{APP_URL}/payu-money-payment
```





