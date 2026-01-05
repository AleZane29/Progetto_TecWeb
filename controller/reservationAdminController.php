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
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["searchName"] ?? "";
    $surname = $_POST["searchSurname"] ?? "";
    $sport = $_POST["filterSport"] ?? "";
    $court = $_POST["filterCourt"] ?? "";
    $date = $_POST["filterDate"] ?? "";
  }
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
                                <button class='action-btn btn-edit' onclick='editReservation(" . $res["id"] . ")'>Modifica</button>
                                <button class='action-btn btn-delete' onclick='openDeleteDialog(" . $res["id"] . ")'>Elimina</button>
                            </div>
                        </td>
                    </tr>";
  }
} else {
  $reservationResult = "<tr><td>Non è possibile visualizzare le prenotazioni, riprovare più tardi</td></tr>";
}
if ($reservationResult == '') {
  $reservationResult = "<tr><td>Nessuna prenotazione</td></tr>";
}
$HTMLPage = str_replace("[adminReservations]", $reservationResult, $HTMLPage);
return $HTMLPage;
