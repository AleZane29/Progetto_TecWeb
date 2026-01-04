<?php
session_start(); 

require_once "../model/database/database.php"; 

use DB\DBConn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nuovoNome = $_POST['nome'];
    $nuovoCognome = $_POST['cognome'];
    $nuovaData = $_POST['data_nascita'];
    
    
    if (!isset($_SESSION['user'])) {
        header("Location: ../views/login.php"); 
        exit;
    }

    $idUtente = $_SESSION['user']; 

    $db = new DBConn();
    if ($db->openConnection()) {
        
        $success = $db->updateUser($idUtente, $nuovoNome, $nuovoCognome, $nuovaData);
        
        $db->closeConnection();

        if ($success) {
            $_SESSION["nameUser"] = $nuovoNome;
            $_SESSION["surnameUser"] = $nuovoCognome;
            $_SESSION["dateUser"] = $nuovaData;

            
            header("Location: ../views/account.php");
            exit;
        } else {
            echo "Errore durante l'aggiornamento dei dati.";
        }
    } else {
        echo "Impossibile connettersi al database.";
    }
} else {
    header("Location: ../views/index.php"); 
    exit;
}
?>