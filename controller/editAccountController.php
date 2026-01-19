<?php
session_start(); 

require_once "../model/database/database.php"; 

use DB\DBConn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nuovoNome = $_POST['name'];
    $nuovoCognome = $_POST['surname'];
    $nuovaData = $_POST['birth'];

    $minAge = 14;

    $birthdate = new DateTime($nuovaData);
    $todaydate = new DateTime();

    $age = $todaydate->diff($birthdate);

    if($age->y < $minAge) {
        header("Location: ../views/account.php"); 
        exit;
    }
    
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
            header("Location: ../views/500.php");;
        }
    } else {
        header("Location: ../views/500.php");
    }
} else {
    header("Location: ../views/500.php");
    exit;
}
?>