<?php
if (!isset($_SESSION)) {
  session_start();
}
require '../controller/loginController.php';
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  include "pages/reservation.html";
} else {
  if (!$HTMLPage) {
    $HTMLPage = file_get_contents('pages/login.html');
    $HTMLPage = str_replace("[loginResult]", ' ', $HTMLPage);
  }
  $HTMLPage = str_replace("[loginAction]", 'reservation.php', $HTMLPage);
  echo ($HTMLPage);
}
include 'components/footer.html';
