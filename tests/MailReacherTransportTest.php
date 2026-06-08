<?php

namespace MailReacher\Laravel\Tests;

use MailReacher\EmailAddress;
use MailReacher\Laravel\MailReacherTransport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailReacherTransportTest extends TestCase
{
    public function test_it_maps_a_symfony_email_to_mail_reacher_payload(): void
    {
        $client = new Support\FakeMailReacherClient();
        $transport = new MailReacherTransport($client);

        $email = (new Email())
            ->from(new Address('noreply@vinyls-collection.fr', 'Vinyls Collection'))
            ->to(new Address('marie@example.com', 'Marie'))
            ->cc('copy@example.com')
            ->bcc('hidden@example.com')
            ->replyTo('support@vinyls-collection.fr')
            ->subject('Bienvenue')
            ->text('Bonjour Marie')
            ->html('<p>Bonjour Marie</p>');

        $transport->send($email, new Envelope(
            new Address('noreply@vinyls-collection.fr', 'Vinyls Collection'),
            [new Address('marie@example.com', 'Marie')],
        ));

        self::assertEquals([
            'to' => [new EmailAddress('marie@example.com', 'Marie')],
            'cc' => [new EmailAddress('copy@example.com')],
            'bcc' => [new EmailAddress('hidden@example.com')],
            'from' => new EmailAddress('noreply@vinyls-collection.fr', 'Vinyls Collection'),
            'reply_to' => 'support@vinyls-collection.fr',
            'subject' => 'Bienvenue',
            'html' => '<p>Bonjour Marie</p>',
            'text' => 'Bonjour Marie',
        ], $client->lastPayload());
    }
}
