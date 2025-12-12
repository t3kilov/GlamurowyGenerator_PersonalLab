<?php
session_start();

if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
    header('Location: logowanie.php');
    exit();
}

$nick = $_SESSION['nick']; 
if ($nick === 'Admin') { 
    header('Location: admin.php'); 
    exit();
} else {
    header('Location: profil.php'); 
    exit();
}
?>