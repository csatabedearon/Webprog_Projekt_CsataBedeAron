<?php
session_start();

// Betöltjük a szükséges fájlokat
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/helpers.php';

foreach (glob(__DIR__ . '/../src/Models/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/../src/Controllers/*.php') as $file) {
    require_once $file;
}
foreach (glob(__DIR__ . '/../src/Services/*.php') as $file) {
    require_once $file;
}

$route = $_GET['page'] ?? 'home';

switch ($route) {
    case 'home':
        view('index');
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'cryptocurrencies':
        (new CryptocurrencyController())->index();
        break;
    case 'favorites':
        (new FavoriteController())->index();
        break;
    case 'favorites_store':
        (new FavoriteController())->store();
        break;
    case 'favorites_delete':
        (new FavoriteController())->destroy();
        break;
    case 'notifications':
        (new NotificationController())->index();
        break;
    case 'notifications_store':
        (new NotificationController())->store();
        break;
    case 'notifications_delete':
        (new NotificationController())->destroy();
        break;
    case 'update_prices':
        (new UpdateController())->updatePrices();
        break;
    default:
        http_response_code(404);
        echo "404 - Oldal nem található";
        break;
}
