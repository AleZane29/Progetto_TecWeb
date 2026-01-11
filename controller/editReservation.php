<?php

require_once "../model/database/database.php";

use DB\DBConn;

if (!isset($_SESSION)) {
  session_start();
}

// Impostiamo l'header per dire che rispondiamo in JSON o Testo
// (Opzionale ma buona pratica)
header('Content-Type: application/json');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();

if ($connessioneOK) {
  $id = isset($_POST['idPrenotazione']) ? $_POST['idPrenotazione'] : null;
  $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
  $court = isset($_POST['court']) ? $_POST['court'] : null;
  $date = isset($_POST['date']) ? $_POST['date'] : null;
  $timeStart = isset($_POST['timeStart']) ? $_POST['timeStart'] : null;
  $timeEnd = isset($_POST['timeEnd']) ? $_POST['timeEnd'] : null;

  // Controllo base: se mancano dati fondamentali, fermiamo tutto
  if (!$id || !$court || !$date || !$timeStart || !$timeEnd) {
      http_response_code(400); // Bad Request
      echo json_encode(["error" => "Dati mancanti"]);
      exit;
  }

  // 1. CONTROLLO SOVRAPPOSIZIONE
  // Passiamo $id come 5° parametro per escludere la prenotazione attuale dal controllo
  // (altrimenti andrebbe in conflitto con se stessa)
  $isOverlapping = $conn->checkOverlap($court, $date, $timeStart, $timeEnd, $id);

  if ($isOverlapping) {
      // Codice 409: Conflict (Risorsa occupata)
      http_response_code(409); 
      // Inviamo un messaggio di errore testuale o JSON
      echo "Attenzione: Il campo $court è già occupato nell'orario selezionato ($timeStart - $timeEnd).";
      $conn->closeConnection();
      exit(); // Interrompiamo lo script qui
  }

  // 2. RECUPERO PREZZO E AGGIORNAMENTO
  $price = $conn->getPriceSport($sport);
  
  $result = $conn->updateReservation($id, $sport, $court, $date, $timeStart, $timeEnd, $price);

  if ($result) {
      http_response_code(200); // OK
      echo json_encode(["success" => true, "message" => "Prenotazione modificata"]);
  } else {
      http_response_code(500); // Server Error
      echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento nel database"]);
  }

} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Errore di connessione al DB"]);
}

$conn->closeConnection();
?>
