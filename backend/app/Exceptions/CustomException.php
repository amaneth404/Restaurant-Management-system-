<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    protected $message;
    protected $statusCode;

    function __construct($message, $code = 500)
    {
        $this->message = $message;
        $this->statusCode = $code;
    }

    function getStatusCode()
    {
        return $this->statusCode;
    }
}
