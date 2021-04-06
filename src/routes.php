<?php

Route::any('payu-money-payment', 'App\Http\Controllers\PayuMoneyController@redirectToPayU')->name('redirectToPayU');
Route::any('payu-money-payment-cancel', 'App\Http\Controllers\PayuMoneyController@paymentCancel')->name('payumoney-cancel');
Route::any('payu-money-payment-success', 'App\Http\Controllers\PayuMoneyController@paymentSuccess')->name('payumoney-success');
