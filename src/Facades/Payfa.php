<?php

declare(strict_types=1);

namespace PayFa\Payfa\Facades;

use Illuminate\Support\Facades\Facade;
use PayFa\Payfa\Exceptions\SendException;
use PayFa\Payfa\Exceptions\VerifyException;
use PayFa\Payfa\Http\Request;

/**
 * This is the payfa facade class.
 */
class Payfa extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payfa';
    }

    /**
     * Send data to payfa.com and init transaction
     *
     * @param $amount
     * @param $redirect
     * @param null $invoice_id
     * @return mixed
     * @throws SendException
     */
    public static function send($amount, $redirect = null, $invoice_id = null, $api = null)
    {
        $send = Request::make('https://payment.payfa.com/v1/api/payment/request', [
            'api' => $api ? $api : config('payfa.api_key'),
            'callback' => $redirect ? $redirect : url(config('payfa.redirect')),
            'amount' => $amount,
            'invoice_id' => $invoice_id,
        ]);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] > 1) {
				$_SESSION['pid'] = $send['status'];
                $send['response']['payment_url'] = 'https://payment.payfa.com/v1/api/payment/gateway/' . $send['status'];

                return $send['response'];
            }

            throw new SendException($send['response']['errorMessage']);
        }

        throw new SendException('خطا در ارسال اطلاعات به Payfa.com. لطفا از برقرار بودن اینترنت و در دسترس بودن payfa.com اطمینان حاصل کنید');
    }

    /**
     * Verify transaction
     *
     * @param $token
     * @return mixed
     * @throws VerifyException
     */
    public static function verify($token, $api = null)
    {
        $verify = Request::make('https://payment.payfa.com/v1/api/payment/verify', [
            'api' => $api ? $api : config('payfa.api_key'),
            'payment_id' => $_SESSION['pid'],
        ]);
        if (isset($verify['status']) && isset($verify['response'])) {
            if ($verify['status'] == 0) {
                return $verify['response'];
            }

            throw new VerifyException($verify['response']['errorMessage']);
        }

        throw new VerifyException('خطا در ارسال اطلاعات به Payfa.com. لطفا از برقرار بودن اینترنت و در دسترس بودن payfa.com اطمینان حاصل کنید');
    }
}
