<?php

namespace MailReacher\Laravel;

use MailReacher\Client;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class MailReacherServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mailreacher.php', 'mailreacher');

        $this->app->singleton(Client::class, function (): Client {
            return new Client(
                apiKey: (string) config('mailreacher.api_key'),
                baseUrl: (string) config('mailreacher.base_url', 'https://mail-reacher.com'),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/mailreacher.php' => config_path('mailreacher.php'),
        ], 'mailreacher-config');

        $this->app->afterResolving('mail.manager', function (MailManager $manager): void {
            $manager->extend('mailreacher', function (): MailReacherTransport {
                return new MailReacherTransport($this->app->make(Client::class));
            });
        });
    }
}
