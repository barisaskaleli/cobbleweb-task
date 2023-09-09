<?php

namespace App\Exception;

class UploadException extends \Exception
{
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