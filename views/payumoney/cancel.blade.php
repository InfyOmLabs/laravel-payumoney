<!doctype html>
<html lang="en">
<head>
     <title>{{ config('app.name') }}</title>
</head>
<body>
<h3>Transaction failed due to: {{ $errorMessage }}</h3>
<h3>Your order status is: {{ $status }} </h3>
<h4>Your transaction id for this transaction is {{ $txnId }}.</h4>
<h4>You may try making the payment by clicking the link below.</h4>
</body>
</html>
