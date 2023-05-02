<?php
session_start();

function url()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return rtrim($protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER["REQUEST_URI"] . '?'), '/');
}


function setFlash(string $key, $type, $message)
{
    $_SESSION[$key] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash(string $key)
{
    if (isset($_SESSION[$key])) {
        $flash = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $flash;
    }
    return false;
}

function guardAuth($redirUrl = false)
{
    if (!(isset($_SESSION['auth']) && $_SESSION['auth']['isLoggedIn']))
        return header("location: " . ($redirUrl ? $redirUrl : url() . "/login.php"));

    return false;
}


function auth()
{
    return $_SESSION['auth'] ?? false;
}
