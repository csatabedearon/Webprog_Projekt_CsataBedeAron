<?php

function view($template, $vars = [])
{
    extract($vars);
    include __DIR__ . '/Views/layout.php';
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function currentUserId()
{
    return $_SESSION['user_id'] ?? null;
}
