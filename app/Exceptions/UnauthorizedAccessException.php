<?php

// app/Exceptions/UnauthorizedAccessException.php
namespace App\Exceptions;

use Exception;

class UnauthorizedAccessException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Unauthorized access", $statusCode = 401)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function render()
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => $this->message
        ], $this->statusCode);
    }
}
