<?php
session_start();

// Giriş
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Rol
function require_role($role) {
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../giris.php");
        exit;
    }
}

// Genel giriş zorunluluğu
function require_login() {
    if(!is_logged_in()) {
        header("Location: ../giris.php");
        exit;
    }
}
?>
