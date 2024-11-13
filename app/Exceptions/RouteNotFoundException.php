<?php

// app/Exceptions/RouteNotFoundException.php
namespace App\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Route not found", $statusCode = 404)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function render()
    {
        return response()->json([
            'error' => 'Not found',
            'message' => $this->message
        ], $this->statusCode);
    }
}
