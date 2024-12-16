<?php

class CoingeckoService
{
    public function updateCryptocurrencies()
    {
        $config = include __DIR__ . '/../config.php';

        $baseUrl = "https://api.coingecko.com/api/v3/coins/markets";
        $vsCurrency = "usd";
        $order = "market_cap_desc";
        $perPage = 250; // Max per_page limit
        $totalCoins = 1000;
        $totalPages = ceil($totalCoins / $perPage);

        $opts = [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $cryptoModel = new Cryptocurrency();
        

        for ($page = 1; $page <= $totalPages; $page++) {
            $url = "$baseUrl?vs_currency=$vsCurrency&order=$order&per_page=$perPage&page=$page&sparkline=false";

            $data = @file_get_contents($url, false, $context);
            if ($data === false) {
                echo "Failed to fetch API data for page $page.\n";
                continue; // Skip to the next page
            }

            $json = json_decode($data, true);
            if (!is_array($json)) {
                echo "Invalid JSON response for page $page.\n";
                continue; // Skip to the next page
            }

            foreach ($json as $coin) {
                $cryptoModel->updateOrCreate($coin['symbol'], [
                    'name' => $coin['name'],
                    'current_price' => $coin['current_price'],
                    'market_cap' => $coin['market_cap'],
                    'volume_24h' => $coin['total_volume'],
                    'icon_url' => $coin['image']
                ]);
            }

            echo "Page $page processed.\n";
        }

        echo "Prices updated.\n";
    }
}
