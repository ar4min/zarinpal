# Payfa.com Laravel

Laravel package to connect to Payfa.com Payment Gateway

## Installation

`composer require payfa/payfa`

## Publish Configurations

`php artisan vendor:publish --provider="PayFa\Payfa\PayfaServiceProvider"`

## Config

Set your api key and redirect url in `.env` file:

    PAYFA_API_KEY=test
    PAYFA_REDIRECT=/payfa/callback
    
## Usage

### Payment Controller

    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use PayFa\Payfa\Exceptions\SendException;
    use PayFa\Payfa\Exceptions\VerifyException;
    use PayFa\Payfa\PayfaPG;
    
    class PaymentController extends Controller
    {
        public function pay()
        {
            $payfa = new PayfaPG();
            $payfa->amount = 1000; // Required, Amount
            $payfa->factorNumber = 'Factor-Number'; // Optional
            $payfa->description = 'Some Description'; // Optional
            $payfa->mobile = '0912XXXXXXX'; // Optional, If you want to show user's saved card numbers in gateway
    
            try {
                $payfa->send();
    
                return redirect($payfa->paymentUrl);
            } catch (SendException $e) {
                throw $e;
            }
        }
    
        public function verify(Request $request)
        {
            $payfa = new PayfaPG();
            $payfa->token = $request->token; // Payfa.com returns this token to your redirect url
    
            try {
                $verify = $payfa->verify(); // returns verify result from payfa.com like (transId, cardNumber, ...)
    
                dd($verify);
            } catch (VerifyException $e) {
                throw $e;
            }
        }
    }

### Routes

    Route::get('/payfa/callback', 'PaymentController@verify');
    
## Usage with facade

Config `aliases` in `config/app.php` :

    'Payfa' => PayFa\Payfa\Facades\Payfa::class
    
*Send*

    Payfa::send($amount, $redirect = null, $factorNumber = null, $mobile = null, $description = null);
    
*Verify*

    Payfa::verify($token);
    
## Security

If you discover any security related issues, please create an issue or email me (omid.m.r73@gmail.com)
    
## License

This repo is open-sourced software licensed under the MIT license.
