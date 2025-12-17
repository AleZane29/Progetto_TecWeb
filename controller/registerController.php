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
    $password = $_POST["password"] ?? "";
    $name = $_POST["name"] ?? "";
    $surname = $_POST["surname"] ?? "";
    $birth = $_POST["birth"] ?? "";
    $queryRes = '';
    try {
      $queryRes = $conn->createUser($name, $surname, $email, $birth, $password);
    } catch (Exception $e) {
      $registerResult = "<p class='error-message' role='alert'>Email già utilizzata</p>";
    }
    if ($queryRes) {
      $user = $conn->getUserByEmail($email);
      $_SESSION["user"] = $user;
      return $HTMLPage = '';
    }
  } else {
    $registerResult = "<p class='error-message' role='alert'>Non è stato possibile effettuare la registrazione, riprovare più tardi</p>";
  }
}
$HTMLPage = str_replace("[registerResult]", $registerResult, $HTMLPage);
return $HTMLPage;
