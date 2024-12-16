<?php

class UpdateController
{
    public function updatePrices()
    {
        if (!isLoggedIn()) {
            redirect('?page=login');
        }

        $service = new CoingeckoService();
        $service->updateCryptocurrencies();

        // Miután lefutott a frissítés, visszairányítjuk a kriptók listájára
        redirect('?page=cryptocurrencies');
    }
}
