<?php

class CryptocurrencyController
{
    public function index()
    {
        $sort = $_GET['sort'] ?? '';
        $p = $_GET['p'] ?? 1;
        $p = max(1, (int)$p);

        $limit = $_GET['limit'] ?? 20;
        $allowedLimits = [20, 50, 100];
        if (!in_array($limit, $allowedLimits)) {
            $limit = 20;
        }

        $crypto = new Cryptocurrency();
        $total = $crypto->countAll();

        $list = $crypto->allPaginated($limit, $p, $sort);
        $totalPages = ceil($total / $limit);

        $from = ($p - 1) * $limit + 1;
        $to = min($from + $limit - 1, $total);

        view('cryptocurrencies', [
            'cryptos' => $list,
            'sort' => $sort,
            'p' => $p,
            'limit' => $limit,
            'total' => $total,
            'from' => $from,
            'to' => $to,
            'totalPages' => $totalPages,
        ]);
    }



    public function show()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $crypto = new Cryptocurrency();
            $c = $crypto->find($id);
            if ($c) {
                echo "<h1>{$c['name']} ({$c['symbol']})</h1>";
                echo "<p>Ár: {$c['current_price']}</p>";
            } else {
                echo "Nincs ilyen kripto!";
            }
        }
    }
}
