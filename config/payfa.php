<?php

return [
    'api_key' => env('PAYFA_API_KEY', 'test'),
    'redirect' => env('PAYFA_REDIRECT', '/payfa/callback'),
];