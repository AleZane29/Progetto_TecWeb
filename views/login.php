<?php
if (!isset($_SESSION)) {
  session_start();
}

require '../controller/loginController.php';
//Controllo se l'utente è autenticato e viene reindirizzato alla sua pagina account
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  header("Location: account.php");
} else {
  //Aggiorno pagina in base ai risultati generati dal controller
  if (!$HTMLPage) {
    $HTMLPage = file_get_contents('pages/login.html');
    $HTMLPage = str_replace("[loginResult]", ' ', $HTMLPage);
  }
  echo ($HTMLPage);
}

include 'components/footer.html';
