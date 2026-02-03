<?php

require_once "../model/database/database.php";

use DB\DBConn;

$HTMLPage = file_get_contents('../views/pages/register.html');

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
    $username = $_POST["username"] ?? "";
    $birth = $_POST["birth"] ?? "";

    $minAge = 14;

    $birthdate = new DateTime($birth);
    $todaydate = new DateTime();

    $age = $todaydate->diff($birthdate);

    if ($age->y < $minAge) {

      $registerResult = "<p class='error-message' role='alert'>Devi avere almeno $minAge anni per registrarti.</p>";

      $HTMLPage = str_replace("[registerResult]", $registerResult, $HTMLPage);
      return $HTMLPage;
    }


    try {
      if($conn->getUserByEmail($email)){
      $registerResult = "<p class='error-message' role='alert'>Email già utilizzata</p>";
      } else {
      $conn->createUser($name, $surname, $username, $email, $birth, $password);
      $user = $conn->getUserByEmail($email);
      $_SESSION["user"] = $user["id"];
      $_SESSION["nameUser"] = $name;
      $_SESSION["surnameUser"] = $surname;
      $_SESSION["usernameUser"] = $username;
      $_SESSION["emailUser"] = $email;
      $_SESSION["dateUser"] = $birth;
      $_SESSION["roleUser"] = $user["ruolo"];
      return $HTMLPage = '';
      }
    } catch (Exception $e) {
      $registerResult = "<p class='error-message' role='alert'>Username già utilizzato</p>";
    }
  } else {
    $registerResult = "<p class='error-message' role='alert'>Non è stato possibile effettuare la registrazione, riprovare più tardi</p>";
  }
}
$HTMLPage = str_replace("[registerResult]", $registerResult, $HTMLPage);
return $HTMLPage;
