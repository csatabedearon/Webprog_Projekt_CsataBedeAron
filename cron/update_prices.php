<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Models/Cryptocurrency.php';
require_once __DIR__ . '/../src/Services/CoingeckoService.php';

$service = new CoingeckoService();
$service->updateCryptocurrencies();
echo "Prices updated.\n";
