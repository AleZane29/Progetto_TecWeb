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
  $title = isset($_POST['title']) ? $_POST['title'] : null;
  $description = isset($_POST['description']) ? $_POST['description'] : null;

  $result = false;

  if ($id !== null)
    $result = $conn->editAnnouncement($id, $title, $description);

  echo json_encode($result);
}
$closeResult = $conn->closeConnection();
