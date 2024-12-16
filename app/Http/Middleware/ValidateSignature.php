<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as BaseValidator;

class ValidateSignature extends BaseValidator
{
    protected $except = [
        // URLs yang dikecualikan dari validasi signature
    ];
}
