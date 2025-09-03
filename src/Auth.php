<?php
namespace LH;

class Auth 
{
    public static function login($username, $password) 
    {
        if ($username === 'admin' && $password === '123123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $username;
            return true;
        }
        return false;
    }

    public static function logout() 
    {
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_user']);
        session_destroy();
    }

    public static function isLoggedIn() 
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    public static function requireAuth() 
    {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }
}