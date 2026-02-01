<?php

require_once "../model/database/database.php";
// require_once "../model/reservationModel.php";

use DB\DBConn;
// use reservationModel\reservationModel;

$conn = new DBConn();
$connessioneOK = $conn->openConnection();
$announcementsResult = '';
$HTMLPage = file_get_contents('../views/pages/home.html');
if ($connessioneOK) {

  $announcements = $conn->getAllAnnouncements();
  foreach ($announcements as $res) {
    $announcementsResult .= "<div class='mySlides fade'>
						<article class='notice-card'>
							<h3>" . $res["titolo"] . "</h3>
							<p>" . $res["descrizione"] . "
							</p>
							<time>" . $res["data"] . "</time>
						</article>
					</div>";
  }
} else {
  $announcementsResult = "<div class='mySlides fade'>
						<article class='notice-card'>
							<h3>Errore nel caricamento</h3>
							<p>Riprovare più tardi
							</p>
						</article>
					</div>";
}
if ($announcementsResult == '') {
  $announcementsResult = "<div class='mySlides fade'>
						<article class='notice-card'>
							<h3>Non ci sono annunci</h3>
							<p>Al momento non ci sono annunci speciali
							</p>
						</article>
					</div>";
}

$HTMLPage = str_replace("<<-ANNOUNCEMENTS->>", $announcementsResult, $HTMLPage);

return $HTMLPage;
?>