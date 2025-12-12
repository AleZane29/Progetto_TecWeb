<?php

$HTMLPage = file_get_contents('../views/pages/login.html');

$loginResult = "<p>Non è stato possibile effettuare il login, riprovare più tardi</p>";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";
  if (!empty($email) && $email === "admin@test.it") {
    if (!empty($password) && $password === "admin") {
      session_regenerate_id(true);
      $_SESSION["user"] = $email;
    } else {
      $loginResult = "<p class='error-message' role='alert'>Password errata</p>";
    }
  } else {
    $loginResult = "<p class='error-message' role='alert'>Email errata</p>";
  }
  $HTMLPage = str_replace("[loginResult]", $loginResult, $HTMLPage);
} else {
  $HTMLPage = '';
}
return $HTMLPage;


// return $error;


// <?php

// require_once ".." . DIRECTORY_SEPARATOR . "php". DIRECTORY_SEPARATOR . "dbConnection.php";
// use DB\DBAccess;

// $paginaHTML = file_get_contents('..' . DIRECTORY_SEPARATOR .'php'. DIRECTORY_SEPARATOR . 'squadra_php.html');

// $connessione = new DBAccess();

// $connessioneOK = $connessione->openDBConnection();

// $atleti = "";
// $stringaAtleti = "";
// $paginaHTML = "";

// if ($connessioneOK) {
// 	$stringaAtleti = .....

// } else {
// 	$stringaAtleti = "<p>I sistemi sono momentaneamente fuori servizio, ci scusiamo per il disagio.</p>";
// }

// $paginaHTML = str_replace("[listaAtleti]", $stringaAtleti, $paginaHTML);
// echo $paginaHTML;

// 
