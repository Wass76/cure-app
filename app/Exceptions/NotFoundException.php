<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Resource not found", $statusCode = 404)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function render()
    {
        return response()->json([
            'error' => 'Not Found',
            'message' => $this->message
        ], $this->statusCode);
    }
}
