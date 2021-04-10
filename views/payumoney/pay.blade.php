<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var hash = '<?php echo $hash ?>';
        function submitPayuForm() {
            if(hash == '') {
                return;
            }
            var payuForm = document.forms.payuForm;
            payuForm.submit();
        }
    </script>
</head>
<body onload="submitPayuForm()">

<div class="container">
    <br/>
    <?php if($formError) { ?>

    <span style="color:red">Please fill all mandatory fields.</span>
    <br/>
    <br/>
    <?php } ?>
    <h2 class="d-flex justify-content-center">PayuMoney Form</h2>

    <div class="row d-flex justify-content-center">
        <form action="<?php echo $action; ?>" method="post" name="payuForm" class="">
            @csrf
            <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
            <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
            <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
            <table class="table table-responsive">
                <tr>
                    <td>Amount: </td>
                    <td><input class="form-control" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" required/></td>
                    <td>First Name: </td>
                    <td><input  class="form-control" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" required/></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><input  class="form-control" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" required/></td>
                    <td>Phone: </td>
                    <td><input  class="form-control" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" required/></td>
                </tr>
                <tr>
                    <td>Product Info: </td>
                    <td colspan="3"><textarea  class="form-control" name="productinfo" required><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea></td>
                </tr>

            </table>
            <?php if(!$hash) { ?>
            <div class="row d-flex justify-content-center">
                <input class="btn btn-primary" type="submit" value="Submit" />

            </div>
            <?php } ?>
            <input name="surl" value="{{route('payumoney-success')}}" hidden/>
            <input name="furl" value="{{route('payumoney-cancel')}}" hidden/>
            <input type="hidden" name="service_provider" value="payu_paisa"  />
        </form>
    </div>
</div>

</body>
</html>
