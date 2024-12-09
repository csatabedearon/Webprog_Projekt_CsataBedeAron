<?php

class Notification
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($user_id, $crypto_id, $trigger_price)
    {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, cryptocurrency_id, trigger_price) VALUES (?,?,?)");
        $stmt->execute([$user_id, $crypto_id, $trigger_price]);
    }

    public function deleteByIdAndUser($id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
    }

    public function byUser($user_id, $sort = '')
    {
        $orderClause = $this->buildOrderClause($sort);

        $stmt = $this->db->prepare("SELECT n.id,n.user_id,n.cryptocurrency_id,n.trigger_price,n.notification_sent,c.name,c.symbol,c.current_price,c.icon_url
                                    FROM notifications n
                                    JOIN cryptocurrencies c ON c.id=n.cryptocurrency_id
                                    WHERE n.user_id=?
                                    $orderClause");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    private function buildOrderClause($sort)
    {
        $allowed = ['name', 'symbol', 'current_price'];
        $default = "ORDER BY c.name ASC";
        if (!$sort) return $default;

        $parts = explode('_', $sort);
        $direction = array_pop($parts);
        $field = implode('_', $parts);

        if (in_array($field, $allowed) && in_array($direction, ['asc', 'desc'])) {
            return "ORDER BY c.$field " . strtoupper($direction);
        }

        return $default;
    }



    public function allPending()
    {
        $stmt = $this->db->query("SELECT n.*, u.email, c.name, c.current_price FROM notifications n 
                                  JOIN users u ON u.id=n.user_id
                                  JOIN cryptocurrencies c ON c.id=n.cryptocurrency_id
                                  WHERE notification_sent=0");
        return $stmt->fetchAll();
    }

    public function markSent($id)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET notification_sent=1 WHERE id=?");
        $stmt->execute([$id]);
    }

    public function exists($user_id, $crypto_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM notifications WHERE user_id=? AND cryptocurrency_id=? AND notification_sent=0");
        $stmt->execute([$user_id, $crypto_id]);
        return (bool)$stmt->fetch();
    }
}
