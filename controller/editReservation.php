<?php

require_once "../model/database/database.php";
use DB\DBConn;

if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$listaOrariOccupati = [];

if ($connessioneOK) {
    $action = isset($_POST['action']) ? $_POST['action'] : 'save';

    if ($action === 'get_slots') {
        $court = $_POST['court'];
        $date = $_POST['date'];
        $sport = $_POST['sport'];
        $excludeId = isset($_POST['excludeId']) ? $_POST['excludeId'] : null;

        $reservationResult = $conn->getSelectedReservations($sport, $court, $date, $excludeId);
        if ($reservationResult) {
            foreach ($reservationResult as $row) {
                $inizio = date('H:i', strtotime($row['ora_inizio']));
                $fine   = date('H:i', strtotime($row['ora_fine']));

                $listaOrariOccupati[] = $inizio . " - " . $fine;
            }
        }
        echo json_encode($listaOrariOccupati);
        
        $conn->closeConnection();
        exit; 
    }

    if ($action === 'save') {
        $id = isset($_POST['idPrenotazione']) ? $_POST['idPrenotazione'] : null;
        $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
        $court = isset($_POST['court']) ? $_POST['court'] : null;
        $date = isset($_POST['date']) ? $_POST['date'] : null;
        $timeStart = isset($_POST['timeStart']) ? $_POST['timeStart'] : null;
        $timeEnd = isset($_POST['timeEnd']) ? $_POST['timeEnd'] : null;

        if (!$id || !$court || !$date || !$timeStart || !$timeEnd) {
            http_response_code(400);
            echo json_encode(["error" => "Dati mancanti"]);
            exit;
        }

        $ruoloUtente = isset($_SESSION['roleUser']) ? $_SESSION['roleUser'] : 'Cliente';
        
        $isAdmin = ($ruoloUtente === 'Admin');

        // SE ADMIN: limite 0 secondi (basta che sia futuro)
        // SE CLIENTE: limite 86400 secondi (24 ore)
        $minSeconds = $isAdmin ? 0 : 86400;

        $existingRes = $conn->getReservationById($id);
        
        if ($existingRes) {
            $existingDateTimeStr = $existingRes['data'] . ' ' . $existingRes['ora_inizio']; 
            
            try {
                $existingTime = new DateTime($existingDateTimeStr);
                $now = new DateTime();
                
                $secondsDiff = $existingTime->getTimestamp() - $now->getTimestamp();
                
                if ($secondsDiff < $minSeconds) {
                    http_response_code(403);
                    $msg = $isAdmin 
                        ? "Impossibile modificare eventi già passati." 
                        : "Troppo tardi! Non puoi modificare una prenotazione se mancano meno di 24 ore.";
                    echo json_encode(["message" => $msg]);
                    exit;
                }
            } catch (Exception $e) {
                // Errore parsing data
            }
        }

        try {
            $now = new DateTime();
            $reservationTime = new DateTime("$date $timeStart");
            $secondsDiff = $reservationTime->getTimestamp() - $now->getTimestamp();

            if ($secondsDiff < $minSeconds) {
                http_response_code(400);
                $msg = $isAdmin 
                    ? "Non puoi spostare una prenotazione nel passato." 
                    : "La nuova data deve essere tra almeno 24 ore.";
                echo json_encode(["message" => $msg]);
                exit;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Errore nel controllo data."]);
            exit;
        }
        
        $isOverlapping = $conn->checkOverlap($sport, $court, $date, $timeStart, $timeEnd, $id);

        if ($isOverlapping) {
            http_response_code(409);
            echo json_encode(["message" => "Orario non disponibile."]);
            exit;
        }

        $price = $conn->getPriceSport($sport);
        $result = $conn->updateReservation($id, $sport, $court, $date, $timeStart, $timeEnd, $price);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Errore DB"]);
        }
    }

} else {
    http_response_code(500);
    echo json_encode(["error" => "Connessione fallita"]);
}

$conn->closeConnection();
?>