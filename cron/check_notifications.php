<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/Notification.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Cryptocurrency.php';

// Egyszerű e-mail küldés (mail function feltételezve)
$notif = new Notification();
$pending = $notif->allPending();
foreach ($pending as $n) {
    // Ha az aktuális ár >= trigger_price
    if ($n['current_price'] >= $n['trigger_price']) {
        $to = $n['email'];
        $subject = "Kripto ár értesítés";
        $message = "A(z) {$n['name']} ára elérte a beállított küszöböt: {$n['current_price']} USD";
        $headers = "From: no-reply@example.com\r\n";
        mail($to, $subject, $message, $headers);
        $notif->markSent($n['id']);
    }
}
echo "Notifications checked.\n";
