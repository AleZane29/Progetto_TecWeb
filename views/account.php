<?php
if (!isset($_SESSION)) {
  session_start();
}

//Controllo se l'utente non è autenticato ed eventuale reindirizzamento a login
if (!(isset($_SESSION["user"]) && $_SESSION["user"] !== null)) {
  header("Location: login.php");
} else {
  include 'pages/account.html';
}

include 'components/footer.html';
