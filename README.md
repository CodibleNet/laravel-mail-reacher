# Laravel Mail Reacher

Official Laravel mail transport for Mail Reacher. It lets existing `Mail`, `Mailable`, notification and `MailMessage` flows send through Mail Reacher without rewriting application email code.

## Installation

```bash
composer require codiblenet/laravel-mail-reacher
```

Publish config if needed:

```bash
php artisan vendor:publish --tag=mailreacher-config
```

## Configuration

```env
MAIL_MAILER=mailreacher
MAILREACHER_API_KEY=mr_test_or_live_key
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Your App"
```

Add the mailer to `config/mail.php` if it is not already present:

```php
'mailers' => [
    'mailreacher' => [
        'transport' => 'mailreacher',
    ],
],
```

That is intentionally the only Mail Reacher-specific environment knob for normal apps: the package sends to the official Mail Reacher API endpoint, and `MAILREACHER_API_KEY` selects the project environment/provider.

Existing Laravel `Mail`, `Mailable`, `Notification` and `MailMessage` flows keep working.

## Example

```php
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

Mail::to('marie@example.com')->send(new WelcomeMail());
```

## Publishing

This repository is intended to be published on Packagist as `codiblenet/laravel-mail-reacher` and versioned with GitHub releases/tags such as `v0.1.0`.

## Testing

```bash
composer test
```
