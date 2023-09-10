<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends \Exception
{
    public const NOT_FOUND = 'Not found';

    private $customData;

    public function __construct(
        $message = self::NOT_FOUND,
        $code = Response::HTTP_NOT_FOUND,
        \Exception $previous = null,
        $customData = null
    )
    {
        $this->customData = $customData;
        parent::__construct($message, $code, $previous);
    }

    public function getCustomData()
    {
        return $this->customData;
    }
}