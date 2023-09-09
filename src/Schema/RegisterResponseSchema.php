<?php

namespace App\Schema;

class RegisterResponseSchema extends AbstractAPIResponseSchema
{

    public function getResponse(?array $response = null): array
    {
        return [
            'status' => $this->getStatus(),
            'statusCode' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'response' => $response
        ];
    }
}