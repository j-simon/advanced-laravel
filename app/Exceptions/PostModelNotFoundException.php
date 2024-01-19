<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostModelNotFoundException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        // unsichtbares handlich
        logger()->info("Post nicht gefunden! ".$this->message);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        // sichtbare Reaktion
        
        return response()->view("exceptions.post_model_not_found");
    }
}
