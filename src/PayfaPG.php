<?php


namespace PayFa\Payfa;

use PayFa\Payfa\Facades\Payfa;

class PayfaPG
{
    public $token;
    public $amount;
    public $redirect;
    public $invoice_id;
    public $paymentUrl;

    /**
     * send
     *
     * @return mixed
     * @throws Exceptions\SendException
     */
    public function send()
    {
        try {
            $send = Payfa::send($this->amount, $this->redirect, $this->invoice_id);

            $this->token = $send['status'];
            $this->paymentUrl = $send['payment_url'];
        } catch (Exceptions\SendException $e) {
            throw $e;
        }
    }

    /**
     * verify
     *
     * @return mixed
     * @throws Exceptions\VerifyException
     */
    public function verify()
    {
        try {
            return Payfa::verify($this->token);
        } catch (Exceptions\VerifyException $e) {
            throw $e;
        }
    }
}