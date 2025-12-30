<?php

require_once "../model/database/database.php";

use DB\DBConn;

$HTMLPage = file_get_contents('../views/pages/reservation.html');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$reservationResult = '';
if ($connessioneOK) {
  $reservationResult = $conn->getUserReservations($_SESSION["user"]);
  foreach ($reservationResult as $res) {
    $_SESSION["user"] = $user["idUser"];
    $_SESSION["nameUser"] = $user["nameUser"];
    $_SESSION["surnameUser"] = $user["surnameUser"];
    $_SESSION["emailUser"] = $user["emailUser"];
    $_SESSION["dateUser"] = $user["dateUser"];
  }
} else {
  $reservationResult = "<p class='error-message' role='alert'>Non è possibile visualizzare le prenotazioni, riprovare più tardi</p>";
}
$HTMLPage = str_replace("[userReservations]", $reservationResult, $HTMLPage);
return $HTMLPage;
