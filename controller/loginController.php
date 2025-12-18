<?php

require_once "../model/database/database.php";

use DB\DBConn;

$HTMLPage = file_get_contents('../views/pages/login.html');

//Controllo se utente già autenticato
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  return $HTMLPage = '';
}

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$loginResult = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($connessioneOK) {
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";
    $user = $conn->checkLogin($email, $password);
    if ($user) {
      $_SESSION["user"] = $user["idUser"];
      $_SESSION["nameUser"] = $user["nameUser"];
      $_SESSION["surnameUser"] = $user["surnameUser"];
      $_SESSION["emailUser"] = $user["emailUser"];
      $_SESSION["dateUser"] = $user["dateUser"];
      return $HTMLPage = '';
    } else {
      // Verifica se l'utente esiste ed è stata inserita una password sbagliata 
      $user = $conn->getUserByEmail($email);
      if ($user) {
        $loginResult = "<p class='error-message' role='alert'>Password errata</p>";
      } else {
        $loginResult = "<p class='error-message' role='alert'>Email errata</p>";
      }
    }
  } else {
    $loginResult = "<p class='error-message' role='alert'>Non è stato possibile effettuare il login, riprovare più tardi</p>";
  }
}
$HTMLPage = str_replace("[loginResult]", $loginResult, $HTMLPage);
return $HTMLPage;
