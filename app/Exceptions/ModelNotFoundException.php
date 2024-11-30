<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
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
            'error' => 'Resource not found',
            'message' => $this->message
        ], $this->statusCode);
    }
}
