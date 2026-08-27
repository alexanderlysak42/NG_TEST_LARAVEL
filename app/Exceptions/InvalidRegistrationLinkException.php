<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InvalidRegistrationLinkException extends Exception
{
    public function render(): Response
    {
        return response()->view('link_invalid', [], 404);
    }
}
