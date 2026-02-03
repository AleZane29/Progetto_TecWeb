<?php
session_start();


require_once "../model/database/database.php";

use DB\DBConn;

header('Content-Type: application/json');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

if (!isset($_GET['sport']) || !isset($_GET['campo']) || !isset($_GET['data'])) {
  if ($connessioneOK) {
    $user_id = $_SESSION["user"];
    $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
    $court = isset($_POST['campo']) ? $_POST['campo'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;

    $timetable = isset($_POST['timetable']) ? $_POST['timetable'] : null;

    if ($date && $timetable) {
        $parts = explode("-", $timetable);
        $timeStart = trim($parts[0]);
        
        $bookingTimestamp = strtotime($date . ' ' . $timeStart);
        
        $limitTimestamp = time() + (24 * 60 * 60);

        if ($bookingTimestamp < $limitTimestamp) {
            exit;
        }
    }

    $parts = explode("-", $timetable);

    $timeStart = trim($parts[0]);
    $timeEnd = trim($parts[1]);
    $price = $conn->getPriceSport($sport);
    $result = false;

    $reservationResult = $conn->createReservation($user_id, $sport, $court, $date, $timeStart, $timeEnd, $price);

    header("Location: ../views/personalReservations.php");
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
            $inizio = date('H:i', strtotime($row['ora_inizio']));
            $fine   = date('H:i', strtotime($row['ora_fine']));

            $listaOrariOccupati[] = $inizio . " - " . $fine;
        }
    }
}

  echo json_encode($listaOrariOccupati);
}
