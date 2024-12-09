<?php

class FavoriteController
{

    public function index()
    {
        if (!isLoggedIn()) {
            redirect('?page=login');
        }
        $sort = $_GET['sort'] ?? '';
        $favModel = new Favorite();
        $favorites = $favModel->byUser(currentUserId(), $sort);
        view('favorites', ['favorites' => $favorites, 'sort' => $sort]);
    }


    public function store()
    {
        $errors = [];
        if (!isLoggedIn()) {
            redirect('?page=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $crypto_id = $_POST['cryptocurrency_id'] ?? null;
            if (empty($crypto_id)) {
                $errors[] = "Nincs kiválasztva kriptovaluta.";
            } else {
                $favModel = new Favorite();
                if ($favModel->exists(currentUserId(), $crypto_id)) {
                    $errors[] = "Ezt a kriptovalutát már hozzáadtad a kedvencekhez.";
                } else {
                    // Ha nincs hiba, hozzáadjuk
                    if (empty($errors)) {
                        $favModel->create(currentUserId(), $crypto_id);
                        // Siker esetén redirect a kedvencek oldalára
                        redirect('?page=favorites');
                    }
                }
            }
        }

        // Ha hibák vannak, vagy GET kérés, akkor újra betöltjük a kedvencek oldalt hibával
        $favModel = new Favorite();
        $favorites = $favModel->byUser(currentUserId());
        view('favorites', ['errors' => $errors, 'favorites' => $favorites]);
    }


    public function destroy()
    {
        if (!isLoggedIn()) {
            redirect('?page=login');
        }
        $id = $_GET['id'] ?? null;
        if ($id) {
            $favModel = new Favorite();
            $favModel->deleteByIdAndUser($id, currentUserId());
        }
        redirect('?page=favorites');
    }
}
