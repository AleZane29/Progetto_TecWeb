<?php

require_once "../model/database/database.php";

use DB\DBConn;

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$announcementsResult = '';
$HTMLPage = file_get_contents('../views/pages/adminAnnouncements.html');

if ($connessioneOK) {

  $announcements = $conn->getAllAnnouncements();
  foreach ($announcements as $res) {
    $announcementsResult .= "<tr>
                        <th scope='row'>" . $res["titolo"] . "</th>
                        <td>" . $res["descrizione"] . "</td>
                        <td>" . $res["data"] . "</td>
                        <td>
                            <div class='actions ann'>
                                <button class='action-btn btn-edit' aria-label='Modifica annuncio " . $res["id"] . "' onclick='openEditDialog(" . $res["id"] . ", this)'>Modifica</button>
                                <button class='action-btn btn-delete' aria-label='Elimina annuncio " . $res["id"] . "' onclick='openDeleteDialog(" . $res["id"] . ")'>Elimina</button>
                            </div>
                        </td>
                    </tr>";
  }
} else {
  $announcementsResult = "<tr><td>Non è possibile visualizzare gli annunci, riprovare più tardi</td></tr>";
}
if ($announcementsResult == '') {
  $announcementsResult = "<tr><td>Nessun annuncio</td></tr>";
}

$HTMLPage = str_replace("[adminAnnouncements]", $announcementsResult, $HTMLPage);

return $HTMLPage;
?>