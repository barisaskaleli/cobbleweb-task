<?php

namespace App\Exception;

class ValidationException extends \Exception
{
    public const VALIDATION_ERROR = 'Validation error! Please check your request data.';

    private $customData;

    public function __construct($message, $code = 0, \Exception $previous = null, $customData = null)
    {
        $this->customData = $customData;
        parent::__construct($message, $code, $previous);
    }

    public function getCustomData()
    {
        return $this->customData;
    }
}