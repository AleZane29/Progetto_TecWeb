<?php
session_start(); // Fondamentale attivare la sessione

require_once "builder.php";
use Builder\Builder;

// Se l'utente non è loggato, via al login
if (!isset($_SESSION['user']) || $_SESSION['user'] === null) {
    header("Location: login.php");
    exit;
}

$paginaHTML = file_get_contents("pages/personalReservations.html"); // Assicurati che il percorso sia giusto

$builder = new Builder();
// Nota: qui immagino che build_account riempia i placeholder <<-NOME->> prendendoli dalla sessione o dal DB
$page = $builder->build_personalReservations($paginaHTML);

echo $page;
?>