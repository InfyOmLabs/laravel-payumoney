<?php

namespace InfyOmLabs\Payu;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class PayuMoneyController.
 */
class PayuMoneyController extends BaseController
{
    const TEST_URL = 'https://sandboxsecure.payu.in';
    const PRODUCTION_URL = 'https://secure.payu.in';

    /**
     * @param Request $request
     *
     * @return string
     */
    public function paymentSuccess(Request $request)
    {
        $input = $request->all();

        $status = $input['status'];
        $firstname = $input['firstname'];
        $amount = $input['amount'];
        $txnId = $input['txnid'];
        $posted_hash = $input['hash'];
        $key = $input['key'];
        $productInfo = $input['productinfo'];
        $email = $input['email'];
        $salt = config('payu.salt_key');

        if (isset($input['additionalCharges'])) {
            $additionalCharges = $input['additionalCharges'];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnId.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productInfo.'|'.$amount.'|'.$txnId.'|'.$key;
        }

        $hash = hash('sha512', $retHashSeq);
        if ($hash != $posted_hash) {
            $errorMessage = 'Invalid Transaction';

            return view('laravel-payumoney::fail', compact('errorMessage'));
        } else {
            return view('laravel-payumoney::success', ['status' => $status, 'txnId' => $txnId, 'amount' => $amount]);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function paymentCancel(Request $request)
    {
        $data = $request->all();
        $validHash = $this->checkHasValidHas($data);
        $viewData = [
            'status' => $data['status'],
            'txnId'  => $data['txnid'],
        ];

        if (!$validHash) {
            $viewData['errorMessage'] = 'Invalid Transaction. Please try again';

            return view('payumoney.cancel', $viewData);
        }

        $viewData['errorMessage'] = $data['error_Message'];

        return view('laravel-payumoney::cancel', $viewData);
    }

    public function checkHasValidHas($data)
    {
        $status = $data['status'];
        $firstname = $data['firstname'];
        $amount = $data['amount'];
        $txnid = $data['txnid'];

        $posted_hash = $data['hash'];
        $key = $data['key'];
        $productinfo = $data['productinfo'];
        $email = $data['email'];
        $salt = '';

        // Salt should be same Post Request
        if (isset($data['additionalCharges'])) {
            $additionalCharges = $data['additionalCharges'];
            $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        } else {
            $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        }

        $hash = hash('sha512', $retHashSeq);

        if ($hash != $posted_hash) {
            return false;
        }

        return true;
    }

    public function redirectToPayU(Request $request)
    {
        $data = $request->all();
        $merchantKey = config('laravel-payumoney.merchant_key');
        $salt = config('laravel-payumoney.salt_key');

        $payuBaseUrl = config('laravel-payumoney.test_mode') ? self::TEST_URL : self::PRODUCTION_URL;
        $action = '';

        $posted = [];
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $posted[$key] = $value;
            }
        }

        $formError = 0;

        if (empty($posted['txnid'])) {
            // Generate random transaction id
            $txnId = substr(hash('sha256', mt_rand().microtime()), 0, 20);
        } else {
            $txnId = $posted['txnid'];
        }
        $hash = '';
        // Hash Sequence
        $hashSequence = 'key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10';
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

                $hash_string .= $salt;

                $hash = strtolower(hash('sha512', $hash_string));
                $action = $payuBaseUrl.'/_payment';
            }
        } elseif (!empty($posted['hash'])) {
            $hash = $posted['hash'];
            $action = $payuBaseUrl.'/_payment';
        }

        return view(
            'laravel-payumoney::pay',
            compact('hash', 'action', 'merchantKey', 'formError', 'txnId', 'posted', 'salt')
        );
    }
}
