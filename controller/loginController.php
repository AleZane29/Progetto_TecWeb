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
    $email_username = $_POST["email_username"] ?? "";
    $password = $_POST["password"] ?? "";
    $user = $conn->getUserByEmail($email_username);
    if(!$user){
      $user = $conn->getUserByUsername($email_username);
    }


    if ($user && password_verify($password, $user['password'])) {
      $_SESSION["user"] = $user["id"];
      $_SESSION["nameUser"] = $user["nome"];
      $_SESSION["surnameUser"] = $user["cognome"];
      $_SESSION["usernameUser"] = $user["username"];
      $_SESSION["emailUser"] = $user["email"];
      $_SESSION["dateUser"] = $user["data_nascita"];
      $_SESSION["roleUser"] = $user["ruolo"];
      return $HTMLPage = '';
    } else {
      if ($user) {
        $loginResult = "<p class='error-message' role='alert'>Password errata</p>";
      } else {
        $loginResult = "<p class='error-message' role='alert'>Email o username errati</p>";
      }
    }
  } else {
    $loginResult = "<p class='error-message' role='alert'>Non è stato possibile effettuare il login, riprovare più tardi</p>";
  }
}
$HTMLPage = str_replace("[loginResult]", $loginResult, $HTMLPage);
return $HTMLPage;
