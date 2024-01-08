<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    // DISABLED BECAUSE LARAVEL AND CHROME ARE FUCKING STUPID
    protected $except = [
        '/*',
        'stripe/*',
//        'user/*',
//        'order/*',
//        'manage/*',
//        'user/logout',
    ];
}
