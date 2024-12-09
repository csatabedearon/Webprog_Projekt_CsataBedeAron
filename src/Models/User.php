<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($username, $email, $passwordHash)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
        $stmt->execute([$username, $email, $passwordHash]);
        return $this->db->lastInsertId();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }

    public function usernameExists($username)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);
        return (bool)$stmt->fetch();
    }
}
