<?php

namespace InfyOm\Payu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class PayuMoneyController
 */
class PayuMoneyController extends Controller
{
    const TEST_URL = 'https://sandboxsecure.payu.in';
    const PRODUCTION_URL = 'https://secure.payu.in';
    
    /**
     * @param  Request  $request
     *
     * @return string
     */
    public function paymentSuccess(Request $request)
    {
        $input = $request->all();

        $status = $input["status"];
        $firstname = $input["firstname"];
        $amount = $input["amount"];
        $txnid = $input["txnid"];
        $posted_hash = $input["hash"];
        $key = $input["key"];
        $productinfo = $input["productinfo"];
        $email = $input["email"];
        $salt = config('payu.salt_key');


        if (isset($input["additionalCharges"])) {
            $additionalCharges = $input["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }
        $hash = hash("sha512", $retHashSeq);
        if ($hash != $posted_hash) {
            return "Invalid Transaction. Please try again";
        } else {
            echo "<h3>Thank You. Your order status is ".$status.".</h3>";
            echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
            echo "<h4>We have received a payment of Rs. ".$amount.". Your order will soon be shipped.</h4>";
        }
    }

    /**
     * @param  Request  $request
     *
     */
    public function paymentCancel(Request $request)
    {
        $data = $request->all();
        $validHash = $this->checkHasValidHas($data);

        if (!$validHash) {
            echo "Invalid Transaction. Please try again";
        } else {
            echo "<h3>Your order status is ". $data["status"].".</h3>";
            echo "<h4>Your transaction id for this transaction is ".$data["txnid"].". You may try making the payment by clicking the link below.</h4>";
        }
        
        $errorMessage = $data['error_Message'];

        return view('payumoney.fail', compact('errorMessage'));
    }
    
    public function checkHasValidHas($data)
    {
        $status = $data["status"];
        $firstname = $data["firstname"];
        $amount = $data["amount"];
        $txnid = $data["txnid"];
        $errorMessage = $data["error_Message"];

        $posted_hash = $data["hash"];
        $key = $data["key"];
        $productinfo = $data["productinfo"];
        $email = $data["email"];
        $salt = "";

        // Salt should be same Post Request

        if (isset($data["additionalCharges"])) {
            $additionalCharges = $data["additionalCharges"];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }

        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            return  false;
        }
        
        return true;
    }

    public function redirectToPayU(Request $request)
    {
        $data = $request->all();
        $MERCHANT_KEY = config('payu.merchant_key');
        $SALT = config('payu.salt_key');

        $PAYU_BASE_URL = config('payu.test_mode') ? self::TEST_URL : self::PRODUCTION_URL;
        $action = '';

        $posted = array();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $posted[$key] = $value;
            }
        }

        $formError = 0;

        if (empty($posted['txnid'])) {
            // Generate random transaction id
            $txnid = substr(hash('sha256', mt_rand().microtime()), 0, 20);
        } else {
            $txnid = $posted['txnid'];
        }
        $hash = '';
// Hash Sequence
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        if (empty($posted['hash']) && sizeof($posted) > 0) {
            if (
                empty($posted['key'])
                || empty($posted['txnid'])
                || empty($posted['amount'])
                || empty($posted['firstname'])
                || empty($posted['email'])
                || empty($posted['phone'])
                || empty($posted['productinfo'])
                || empty($posted['surl'])
                || empty($posted['furl'])
                || empty($posted['service_provider'])
            ) {
                $formError = 1;
            } else {
//                $posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
                $hashVarsSeq = explode('|', $hashSequence);
                $hash_string = '';
                foreach ($hashVarsSeq as $hash_var) {
                    $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                    $hash_string .= '|';
                }

                $hash_string .= $SALT;


                $hash = strtolower(hash('sha512', $hash_string));
                $action = $PAYU_BASE_URL.'/_payment';

            }
        } elseif (!empty($posted['hash'])) {
            $hash = $posted['hash'];
            $action = $PAYU_BASE_URL.'/_payment';

        }

        return view('payumoney.pay',
            compact('hash', 'action', 'MERCHANT_KEY', 'formError', 'txnid', 'posted', 'SALT'));
    }
}
