<?php

require_once "../model/database/database.php";
// require_once "../model/reservationModel.php";

use DB\DBConn;
// use reservationModel\reservationModel;

$HTMLPage = file_get_contents('../views/pages/reservationAdmin.html');

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$reservationResult = '';
$sportsResult = '';
$courtsResult = '';
if ($connessioneOK) {
  $sports = $conn->getAllSports();
  foreach ($sports as $res) {
    $sportsResult .= "<option value=" . $res["nome"] . ">" . $res["nome"] . "</option>";
  }
  $courts = $conn->getAllCourts();
  foreach ($courts as $res) {
    $courtsResult .= "<option style='display: none;' data-court-id=" . $res["tipo"] . $res["numero"] . " value=" . $res["numero"] . "> Campo " . $res["numero"] . "</option>";
  }

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
                                <button class='action-btn btn-edit' onclick='openEditDialog(" . $res["id"] . ", this)'>Modifica</button>
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
$HTMLPage = str_replace("[sports]", $sportsResult, $HTMLPage);
$HTMLPage = str_replace("[courts]", $courtsResult, $HTMLPage);
$HTMLPage = str_replace("[adminReservations]", $reservationResult, $HTMLPage);

return $HTMLPage;
