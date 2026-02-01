<?php
session_start(); // Fondamentale attivare la sessione

require_once "builder.php";
use Builder\Builder;

// Se l'utente non è loggato, via al login
if (!isset($_SESSION['user']) || $_SESSION['user'] === null) {
    header("Location: login.php");
    exit;
}


$builder = new Builder();
$page = $builder->build_account();

echo $page;
?>