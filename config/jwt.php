<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Public Key Path
    |--------------------------------------------------------------------------
    |
    | Path to the public key file used for verifying JWT signatures.
    | This key should be provided by your authentication service.
    |
    */
    'public_key_path' => env('JWT_PUBLIC_KEY_PATH', base_path('keys/public.pem')),

    /*
    |--------------------------------------------------------------------------
    | Supported Algorithms
    |--------------------------------------------------------------------------
    |
    | Algorithms allowed for JWT signature verification.
    | Only asymmetric algorithms should be used for microservices.
    |
    */
    'algorithms' => ['RS256'],

    /*
    |--------------------------------------------------------------------------
    | Clock Skew Leeway
    |--------------------------------------------------------------------------
    |
    | Number of seconds to allow for clock skew between services.
    | Helps handle small time differences between servers.
    |
    */
    'leeway' => 60,
];
