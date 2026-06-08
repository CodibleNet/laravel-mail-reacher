<?php

namespace MailReacher\Laravel;

use MailReacher\Client;
use MailReacher\EmailAddress;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class MailReacherTransport extends AbstractTransport
{
    public function __construct(private readonly object $client)
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'mailreacher';
    }

    protected function doSend(SentMessage $message): void
    {
        $original = $message->getOriginalMessage();

        if (! $original instanceof Email) {
            return;
        }

        $this->client->emails()->send($this->payloadFromEmail($original));
    }

    /**
     * @return array<string, mixed>
     */
    public function payloadFromEmail(Email $email): array
    {
        $payload = [
            'to' => $this->addresses($email->getTo()),
            'cc' => $this->addresses($email->getCc()),
            'bcc' => $this->addresses($email->getBcc()),
            'from' => $this->firstAddress($email->getFrom()),
            'reply_to' => $this->replyTo($email),
            'subject' => $email->getSubject(),
            'html' => $email->getHtmlBody(),
            'text' => $email->getTextBody(),
        ];

        return array_filter($payload, static function (mixed $value): bool {
            return $value !== null && $value !== [] && $value !== '';
        });
    }

    /**
     * @param list<Address> $addresses
     * @return list<EmailAddress>
     */
    private function addresses(array $addresses): array
    {
        return array_map(
            static fn (Address $address): EmailAddress => new EmailAddress($address->getAddress(), $address->getName() ?: null),
            $addresses,
        );
    }

    /**
     * @param list<Address> $addresses
     */
    private function firstAddress(array $addresses): ?EmailAddress
    {
        if ($addresses === []) {
            return null;
        }

        $address = $addresses[0];

        return new EmailAddress($address->getAddress(), $address->getName() ?: null);
    }

    private function replyTo(Email $email): ?string
    {
        $addresses = $email->getReplyTo();

        if ($addresses === []) {
            return null;
        }

        return $addresses[0]->getAddress();
    }
}
