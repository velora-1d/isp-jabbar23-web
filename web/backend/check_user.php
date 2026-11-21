<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@isp.com';
$user = User::where('email', $email)->first();

if ($user) {
    echo "USER_FOUND\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    if (Hash::check('admin123', $user->password)) {
        echo "PASSWORD_MATCH: OK\n";
    } else {
        echo "PASSWORD_MATCH: FAILED\n";
    }
} else {
    echo "USER_NOT_FOUND\n";
}
