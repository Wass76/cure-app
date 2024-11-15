<?php

namespace App\Exceptions;

use Exception;

class DublicatedFileNameException extends Exception{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Dublicate file name", $statusCode = 400)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    public function render()
    {
        return response()->json([
            'error' => 'Dublicate file name',
            'message' => $this->message
        ], $this->statusCode);
    }
}
