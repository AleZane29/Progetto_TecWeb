<?php

require_once "../model/database/database.php";

use DB\DBConn;

if (!isset($_SESSION)) {
  session_start();
}

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

if ($connessioneOK) {
  $id = isset($_POST['idPrenotazione']) ? $_POST['idPrenotazione'] : null;
  $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
  $court = isset($_POST['court']) ? $_POST['court'] : null;
  $date = isset($_POST['date']) ? $_POST['date'] : null;
  $timeStart = isset($_POST['timeStart']) ? $_POST['timeStart'] : null;
  $timeEnd = isset($_POST['timeEnd']) ? $_POST['timeEnd'] : null;
  $price = $conn->getPriceSport($sport);
  $result = false;

  if ($id !== null)
    $result = $conn->updateReservation($id, $sport, $court, $date, $timeStart, $timeEnd, $price);

  echo json_encode($result);
}
$closeResult = $conn->closeConnection();
