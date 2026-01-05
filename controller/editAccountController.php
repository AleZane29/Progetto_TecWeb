<?php
session_start(); 

require_once "../model/database/database.php"; 

use DB\DBConn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Aggiornati per corrispondere ai nuovi name dell'HTML
    $nuovoNome = $_POST['name'];
    $nuovoCognome = $_POST['surname'];
    $nuovaData = $_POST['birth'];
    
    if (!isset($_SESSION['user'])) {
        header("Location: ../views/login.php"); 
        exit;
    }

    $idUtente = $_SESSION['user']; 

    $db = new DBConn();
    if ($db->openConnection()) {
        
        // Assicurati che updateUser nel database accetti questi parametri
        $success = $db->updateUser($idUtente, $nuovoNome, $nuovoCognome, $nuovaData);
        
        $db->closeConnection();

        if ($success) {
            // Aggiorniamo la sessione con i nuovi dati per vederli subito
            $_SESSION["nameUser"] = $nuovoNome;
            $_SESSION["surnameUser"] = $nuovoCognome;
            $_SESSION["dateUser"] = $nuovaData;

            // Rimandiamo alla pagina account (che ora è anche la pagina di modifica)
            header("Location: ../views/account.php");
            exit;
        } else {
            // Qui potresti gestire l'errore meglio, magari con un parametro GET ?error=1
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