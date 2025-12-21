<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Mail Configuration Check:\n";
echo "========================\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER', 'not set') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST', 'not set') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT', 'not set') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME', 'not set') . "\n";
echo "MAIL_PASSWORD: " . (env('MAIL_PASSWORD') ? '***set***' : 'not set') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION', 'not set') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'not set') . "\n";
echo "MAIL_FROM_NAME: " . env('MAIL_FROM_NAME', 'not set') . "\n";
echo "\n";

echo "Config values:\n";
echo "Default mailer: " . config('mail.default') . "\n";
echo "From address: " . config('mail.from.address') . "\n";
echo "From name: " . config('mail.from.name') . "\n";




