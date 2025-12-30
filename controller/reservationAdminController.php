<?php

require_once "../model/database/database.php";
// require_once "../model/reservationModel.php";

use DB\DBConn;
// use reservationModel\reservationModel;

$HTMLPage = file_get_contents('../views/pages/reservationAdmin.html');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$reservationResult = '';
if ($connessioneOK) {
  $reservations = $conn->getAllReservations();
  foreach ($reservations as $res) {
    $reservationResult .= "<tr>
                        <td>" . $res["utente"] . "</td>
                        <td>" . $res["tipo_campo"] . "</td>
                        <td>Campo " . $res["numero_campo"] . "</td>
                        <td>" . $res["data"] . "</td>
                        <td>" . $res["ora_inizio"] . " - " . $res["ora_fine"] . "</td>
                        <td>" . $res["prezzo"] . "€</td>
                        <td>
                            <div class='actions'>
                                <button class='action-btn btn-edit'>Modifica</button>
                                <button class='action-btn btn-delete'>Elimina</button>
                            </div>
                        </td>
                    </tr>";
  }
} else {
  $reservationResult = "<p class='error-message' role='alert'>Non è possibile visualizzare le prenotazioni, riprovare più tardi</p>";
}
$HTMLPage = str_replace("[adminReservations]", $reservationResult, $HTMLPage);
return $HTMLPage;



// $sport = htmlspecialchars($_POST['sport']);
// $date = htmlspecialchars($_POST['date']);
// $timetable = htmlspecialchars($_POST['timetable']);

// $utente = $_SESSION["user"];

// #intanto
// switch ($sport) {
//   case 'calcetto':
//     $numero_campo = 1;
//     break;

//   case 'basket':
//     $numero_campo = 6;
//     break;

//   case 'tennis':
//     $numero_campo = 3;
//     break;

//   default:
//     // Eseguito se nessuno dei casi precedenti corrisponde
//     echo "campo non riconosciuto.";
//     break;
// }

// $parti = explode("-", $timetable, 2);
// $orario_inizio = $parti[0];
// $orario_fine = $parti[1];

// $reservation = new reservationModel();
// $reservation->createReservation($utente, $numero_campo, $sport, $date, $orario_inizio, $orario_fine);
