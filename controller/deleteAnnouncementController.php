<?php

require_once "../model/database/database.php";

use DB\DBConn;

if (!isset($_SESSION)) {
  session_start();
}

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

if ($connessioneOK) {
  $id = isset($_POST['idAnnuncio']) ? $_POST['idAnnuncio'] : null;

  $result = false;

  if ($id !== null)
    $result = $conn->removeAnnouncement($id);

  echo json_encode($result);
}
$closeResult = $conn->closeConnection();
