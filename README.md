# Laravel Mail Reacher

Laravel mail transport for Mail Reacher.

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
MAILREACHER_API_KEY=mr_live_xxx
MAILREACHER_BASE_URL=https://mail-reacher.com
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

Existing Laravel `Mail`, `Mailable`, `Notification` and `MailMessage` flows keep working.

## Testing

```bash
composer test
```
