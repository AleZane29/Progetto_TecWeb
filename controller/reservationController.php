<?php

require_once "../model/database/database.php";
require_once "../model/reservationModel.php";

use DB\DBConn;
use reservationModel\reservationModel;

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



$sport = htmlspecialchars($_POST['sport']);
$date = htmlspecialchars($_POST['date']);
$timetable = htmlspecialchars($_POST['timetable']);

$utente = $_SESSION["user"];

#intanto
switch ($sport) {
  case 'calcetto':
    $numero_campo = 1;
    break;

  case 'basket':
    $numero_campo = 6;
    break;

  case 'tennis':
    $numero_campo = 3;
    break;

  default:
    // Eseguito se nessuno dei casi precedenti corrisponde
    echo "campo non riconosciuto.";
    break;
}

$parti = explode("-", $timetable, 2);
$orario_inizio = $parti[0];
$orario_fine = $parti[1];

$reservation = new reservationModel();
$reservation->createReservation($utente, $numero_campo, $sport, $date, $orario_inizio, $orario_fine);
