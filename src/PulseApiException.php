<?php

declare(strict_types=1);

namespace EarlyForge\Pulse;

final class PulseApiException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly ?int $statusCode = null,
        private readonly ?string $detail = null,
    ) {
        parent::__construct($message);
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }
}
