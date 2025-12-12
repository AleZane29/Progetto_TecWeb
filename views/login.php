<?php
if (!isset($_SESSION)) {
  session_start();
}
if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
  header("Location: ./account.php");
}

require '../controller/loginController.php';
if (!$HTMLPage) {
  $HTMLPage = file_get_contents('pages/login.html');
  $HTMLPage = str_replace("[loginResult]", ' ', $HTMLPage);
}
echo ($HTMLPage);
include 'components/footer.html';


// if (!empty($error)) {
//   echo ("<p class='error-message' role='alert'>$error</p>");
// } else {
//   if (!empty($success)) {
//     echo ("<p class='success-message' role='alert'>$success</p>");
//   }
// }
