<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esc($str){
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(){
    return isset($_SESSION['user_id']);
}

function requireLogin(){
    if(!isLoggedIn()){
        header("Location: login.php");
        exit;
    }
}

function requireRole($role){
    if(!isset($_SESSION['role']) || $_SESSION['role'] !== $role){
        header("Location: dashboard.php?err=unauthorized");
        exit;
    }
}

function flash($key){
    if(isset($_SESSION[$key])){
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}
?>