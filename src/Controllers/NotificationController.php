<?php

class NotificationController
{

    public function index()
    {
        if (!isLoggedIn()) {
            redirect('?page=login');
        }
        $sort = $_GET['sort'] ?? '';
        $notifModel = new Notification();
        $notifications = $notifModel->byUser(currentUserId(), $sort);
        view('notifications', ['notifications' => $notifications, 'sort' => $sort]);
    }


    public function store()
    {
        $errors = [];
        if (!isLoggedIn()) {
            redirect('?page=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $crypto_id = $_POST['cryptocurrency_id'] ?? null;
            $trigger_price = $_POST['trigger_price'] ?? null;

            if (empty($crypto_id)) {
                $errors[] = "Nincs kiválasztva kriptovaluta.";
            }
            if (empty($trigger_price) || !is_numeric($trigger_price)) {
                $errors[] = "Érvényes trigger árat adj meg.";
            }

            $notifModel = new Notification();

            // Ellenőrizzük, hogy nincs-e már értesítés ugyanarra a kriptóra
            if ($notifModel->exists(currentUserId(), $crypto_id)) {
                $errors[] = "Már beállítottál értesítést erre a kriptóra.";
            }

            if (empty($errors)) {
                $notifModel->create(currentUserId(), $crypto_id, $trigger_price);
                redirect('?page=notifications');
            }
        }

        $notifModel = new Notification();
        $notifications = $notifModel->byUser(currentUserId());
        view('notifications', ['errors' => $errors, 'notifications' => $notifications]);
    }


    public function destroy()
    {
        if (!isLoggedIn()) {
            redirect('?page=login');
        }
        $id = $_GET['id'] ?? null;
        if ($id) {
            $notifModel = new Notification();
            $notifModel->deleteByIdAndUser($id, currentUserId());
        }
        redirect('?page=notifications');
    }
}
