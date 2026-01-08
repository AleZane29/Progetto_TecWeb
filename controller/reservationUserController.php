<?php
session_start();


require_once "../model/database/database.php";
// require_once "../model/reservationModel.php";

use DB\DBConn;
// use reservationModel\reservationModel;

header('Content-Type: application/json');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

// Controllo parametri
if (!isset($_GET['sport']) || !isset($_GET['campo']) || !isset($_GET['data'])) {
  if ($connessioneOK) {
    $user_id = $_SESSION["user"];
    $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
    $court = isset($_POST['campo']) ? $_POST['campo'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;

    $timetable = isset($_POST['timetable']) ? $_POST['timetable'] : null;

    $parts = explode("-", $timetable);

    $timeStart = trim($parts[0]);
    $timeEnd = trim($parts[1]);
    $price = $conn->getPriceSport($sport);
    $result = false;

    $reservationResult = $conn->createReservation($user_id, $sport, $court, $date, $timeStart, $timeEnd, $price);

    header("Location: ../views/account.php");
  } else {

    header("Location: ../views/500.php");
  }
} else {
  $sport = $_GET['sport'];
  $campo = $_GET['campo'];
  $data = $_GET['data'];
  $listaOrariOccupati = [];


  if ($connessioneOK) {
    $reservationResult = $conn->getSelectedReservations($sport, $campo, $data);

    if ($reservationResult) {
      foreach ($reservationResult as $row) {
        // Deve corrispondere ESATTAMENTE al formato usato in JS (es. "9:30-11:00" o "09:30")
        $listaOrariOccupati[] = $row['ora_inizio'] . "-" . $row['ora_fine'];
      }
    }
  }

  echo json_encode($listaOrariOccupati);
}
