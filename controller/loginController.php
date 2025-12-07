<?php
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";

  if (!empty($email) && $email === "admin@test.it") {
    if (!empty($password) && $password === "123456") {
      $success = "Login effettuato con successo!";
      session_regenerate_id(true);
      $_SESSION["user"] = $email;
    } else {
      $error = "Password errata";
    }
  } else {
    $error = "Email errata";
  }
}
