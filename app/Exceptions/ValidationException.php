<?php

// app/Exceptions/ValidationException.php
namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $message;
    protected $errors;

    public function __construct($errors = [], $message = "Validation error")
    {
        $this->message = $message;
        $this->errors = $errors;
        parent::__construct($message);
    }

    public function render()
    {
        return response()->json([
            'error' => $this->message,
            'messages' => $this->errors,
        ], 422);
    }
}
