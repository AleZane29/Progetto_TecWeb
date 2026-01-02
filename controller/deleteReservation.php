<?php

require_once "../model/database/database.php";

use DB\DBConn;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// setlocale(LC_ALL, 'it_IT');

if (!isset($_SESSION)) {
  session_start();
}

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

if ($connessioneOK) {
  $id = isset($_POST['idPrenotazione']) ? $_POST['idPrenotazione'] : null;

  $result = false;

  if ($id !== null)
    $result = $conn->removePrenotation($id);

  echo json_encode($result);
}
$closeResult = $conn->closeConnection();
