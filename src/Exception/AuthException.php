<?php

namespace App\Exception;

class AuthException extends \Exception
{
    public const UNAUTHENTICATE_USER = 'User is not authenticated';

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