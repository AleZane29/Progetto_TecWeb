<?php

require_once "../model/database/database.php";

use DB\DBConn;

$HTMLPage = file_get_contents('../views/pages/register.html');

//Controllo se utente già autenticato
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  return $HTMLPage = '';
}

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$registerResult = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($connessioneOK) {
    $email = $_POST["email"] ?? "";
    $passwordInChiaro = $_POST["password"] ?? "";
    $password = password_hash($passwordInChiaro, PASSWORD_DEFAULT);
    $name = $_POST["name"] ?? "";
    $surname = $_POST["surname"] ?? "";
    $birth = $_POST["birth"] ?? "";
    $user = '';
    try {
      $user = $conn->createUser($name, $surname, $email, $birth, $password);
      $_SESSION["user"] = $user;
      $_SESSION["nameUser"] = $name;
      $_SESSION["surnameUser"] = $surname;
      $_SESSION["emailUser"] = $email;
      $_SESSION["dateUser"] = $birth;
      return $HTMLPage = '';
    } catch (Exception $e) {
      $registerResult = "<p class='error-message' role='alert'>Email già utilizzata</p>";
    }
  } else {
    $registerResult = "<p class='error-message' role='alert'>Non è stato possibile effettuare la registrazione, riprovare più tardi</p>";
  }
}
$HTMLPage = str_replace("[registerResult]", $registerResult, $HTMLPage);
return $HTMLPage;
