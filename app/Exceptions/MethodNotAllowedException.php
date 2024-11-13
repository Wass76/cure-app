<?php

// app/Exceptions/MethodNotAllowedException.php
namespace App\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Method not allowed", $statusCode = 405)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function render()
    {
        return response()->json([
            'error' => 'Method not allowed',
            'message' => $this->message
        ], $this->statusCode);
    }
}
