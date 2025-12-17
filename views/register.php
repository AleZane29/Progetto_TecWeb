<?php
if (!isset($_SESSION)) {
  session_start();
}
require '../controller/registerController.php';
//Controllo se l'utente è autenticato e viene reindirizzato alla sua pagina account
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  header("Location: account.php");
} else {
  //Aggiorno pagina in base ai risultati generati dal controller
  if (!$HTMLPage) {
    $HTMLPage = file_get_contents('pages/register.html');
    $HTMLPage = str_replace("[registerResult]", ' ', $HTMLPage);
  }
  echo ($HTMLPage);
}
include 'components/footer.html';
