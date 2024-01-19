<?php

namespace App\Exceptions;

use App\Mail\Quote;
use App\Mail\Quote2;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class CalcException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
        info('error, falsche Handhabung von calc');

        Mail::to('bug@laravel.io')->send(new Quote2("text","text"));
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        //
        return $this->message ? $this->message : abort(500);
    }
}
