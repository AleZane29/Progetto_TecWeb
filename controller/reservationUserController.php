<?php

require_once "../model/reservationModel.php";

use reservationModel\reservationModel;

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
