<?php

namespace App\Schema;

use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAPIResponseSchema implements JsonSerializable
{
    private const SUCCESS_STATUS = 'success';
    private const FAILED_STATUS = 'failed';
    public const SUCCESS_CODE = Response::HTTP_OK;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $exceptionCode;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function setSuccessStatus(): self
    {
        $this->status = self::SUCCESS_STATUS;

        return $this;
    }

    /**
     * @return $this
     */
    public function setFailedStatus(): self
    {
        $this->status = self::FAILED_STATUS;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?? '';
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode ?? self::SUCCESS_CODE;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExceptionCode(): ?string
    {
        return $this->exceptionCode;
    }

    /**
     * @param string|null $exceptionCode
     * @return AbstractAPIResponseSchema
     */
    public function setExceptionCode(?string $exceptionCode): AbstractAPIResponseSchema
    {
        $this->exceptionCode = $exceptionCode;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result[] = [
            'status' => $this->getStatus(),
            'statusCode' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'response' => $this->getResponse()
        ];

        if (($exceptionTypeCode = $this->getExceptionCode()) !== null) {
            $result[] = [
                'exceptionCode' => $exceptionTypeCode
            ];
        }

        return array_merge(...$result);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return mixed
     */
    abstract public function getResponse();
}
