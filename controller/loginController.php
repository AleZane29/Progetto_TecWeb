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
    $user = $conn->getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION["user"] = $user["id"];
      $_SESSION["nameUser"] = $user["nome"];
      $_SESSION["surnameUser"] = $user["cognome"];
      $_SESSION["emailUser"] = $user["email"];
      $_SESSION["dateUser"] = $user["data_nascita"];
      return $HTMLPage = '';
    } else {
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
