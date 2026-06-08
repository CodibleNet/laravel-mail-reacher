<?php

namespace MailReacher\Laravel\Tests\Support;

class FakeMailReacherClient
{
    /** @var array<string, mixed>|null */
    private ?array $lastPayload = null;

    public function emails(): self
    {
        return $this;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function send(array $payload): array
    {
        $this->lastPayload = $payload;

        return ['data' => ['id' => 123, 'status' => 'queued']];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function lastPayload(): ?array
    {
        return $this->lastPayload;
    }
}
