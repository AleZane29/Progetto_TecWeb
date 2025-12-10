<?php



include 'components/header.html';
include 'pages/login.html';
include 'components/footer.html';


if (!empty($error)) {
  echo ("<p class='error-message' role='alert'>$error</p>");
} else {
  if (!empty($success)) {
    echo ("<p class='success-message' role='alert'>$success</p>");
  }
}


?>


