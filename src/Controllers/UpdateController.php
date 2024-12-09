<?php

class UpdateController
{
    public function updatePrices()
    {
        // Auth ellenőrzés optional, ha bárki frissíthet, akkor nem kell.
        // Ha csak bejelentkezett user frissíthet:
        if (!isLoggedIn()) {
            redirect('?page=login');
        }

        $service = new CoingeckoService();
        $service->updateCryptocurrencies();

        // Miután lefutott a frissítés, visszairányítjuk mondjuk a kriptók listájára
        redirect('?page=cryptocurrencies');
    }
}
