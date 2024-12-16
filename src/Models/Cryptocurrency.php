<?php

class Cryptocurrency
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateOrCreate($symbol, $data)
    {
        $stmt = $this->db->prepare("SELECT id FROM cryptocurrencies WHERE symbol=?");
        $stmt->execute([$symbol]);
        $exists = $stmt->fetch();

        if ($exists) {
            $stmt = $this->db->prepare("UPDATE cryptocurrencies SET name=?, current_price=?, market_cap=?, volume_24h=?, icon_url=?, last_update=NOW() WHERE symbol=?");
            $stmt->execute([$data['name'], $data['current_price'], $data['market_cap'], $data['volume_24h'], $data['icon_url'], $symbol]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cryptocurrencies (name,symbol,current_price,market_cap,volume_24h,icon_url,last_update) VALUES (?,?,?,?,?,?,NOW())");
            $stmt->execute([$data['name'], $symbol, $data['current_price'], $data['market_cap'], $data['volume_24h'], $data['icon_url']]);
        }
    }

    public function all($limit = 20, $sort = '')
    {
        $allowed = ['name', 'symbol', 'current_price'];
        $orderClause = "ORDER BY market_cap DESC";

        if ($sort) {
            $parts = explode('_', $sort);
            // Az utolsó elem lesz az irány (asc vagy desc)
            $direction = array_pop($parts); // pl. 'desc'
            // A maradék részekből áll a mezőnév
            $field = implode('_', $parts); // pl. 'current_price'

            if (in_array($field, $allowed) && in_array($direction, ['asc', 'desc'])) {
                $orderClause = "ORDER BY $field " . strtoupper($direction);
            }
        }

        $stmt = $this->db->prepare("SELECT * FROM cryptocurrencies $orderClause LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }




    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM cryptocurrencies WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM cryptocurrencies");
        $row = $stmt->fetch();
        return $row['cnt'];
    }

    public function allPaginated($limit = 20, $page = 1, $sort = '')
    {
        $allowed = ['name', 'symbol', 'current_price'];
        $orderClause = "ORDER BY market_cap DESC";

        if ($sort) {
            $parts = explode('_', $sort);
            $direction = array_pop($parts);
            $field = implode('_', $parts);
            if (in_array($field, $allowed) && in_array($direction, ['asc', 'desc'])) {
                $orderClause = "ORDER BY $field " . strtoupper($direction);
            }
        }

        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM cryptocurrencies $orderClause LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
