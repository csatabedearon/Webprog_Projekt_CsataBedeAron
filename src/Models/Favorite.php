<?php

class Favorite
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($user_id, $crypto_id)
    {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, cryptocurrency_id) VALUES (?,?)");
        $stmt->execute([$user_id, $crypto_id]);
    }

    public function deleteByIdAndUser($id, $user_id)
    {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE id=? AND user_id=?");
        $stmt->execute([$id, $user_id]);
    }

    public function byUser($user_id, $sort = '')
    {
        $orderClause = $this->buildOrderClause($sort);

        $stmt = $this->db->prepare("SELECT f.id,f.user_id,f.cryptocurrency_id,c.name,c.symbol,c.current_price,c.icon_url
                                    FROM favorites f
                                    JOIN cryptocurrencies c ON c.id=f.cryptocurrency_id
                                    WHERE f.user_id=?
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
        $direction = array_pop($parts); // utolsó elem az irány (asc/desc)
        $field = implode('_', $parts);  // a maradék az oszlopnév (pl. 'current_price')

        if (in_array($field, $allowed) && in_array($direction, ['asc', 'desc'])) {
            return "ORDER BY c.$field " . strtoupper($direction);
        }

        return $default;
    }



    public function exists($user_id, $crypto_id)
    {
        $stmt = $this->db->prepare("SELECT id FROM favorites WHERE user_id=? AND cryptocurrency_id=?");
        $stmt->execute([$user_id, $crypto_id]);
        return (bool)$stmt->fetch();
    }
}
