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

  $result = false;

  if ($id !== null)
    $result = $conn->removePrenotation($id);

  echo json_encode($result);
}
$closeResult = $conn->closeConnection();
