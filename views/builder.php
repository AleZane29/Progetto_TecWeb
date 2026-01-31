<?php

namespace Builder;


class Builder
{
  // Properties
  public $headerHTML;
  public $footerHTML;
  public $personalReservationsLinkHTML;

  function __construct()
  {
    if (!isset($_SESSION)) {
      session_start();
    }

    $this->headerHTML = file_get_contents("components/header.html");
    $this->personalReservationsLinkHTML = file_get_contents("components/personalReservationsLink.html");

    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      $this->headerHTML = str_replace("<<-ACCOUNT->>", $_SESSION["nameUser"], $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNTARIA->>", $_SESSION["nameUser"], $this->headerHTML);
      if ($_SESSION["roleUser"] === "Admin") {
        $this->headerHTML = str_replace("<<-PERSONALRESERVATIONSLINK->>", ' ', $this->headerHTML);
        $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenotazioni", $this->headerHTML);
      } else {
        $this->headerHTML = str_replace("<<-PERSONALRESERVATIONSLINK->>", $this->personalReservationsLinkHTML, $this->headerHTML);
        $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenota", $this->headerHTML);
      }
    } else {
      $this->headerHTML = str_replace("<<-PERSONALRESERVATIONSLINK->>", ' ', $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNT->>", "<span lang=\"en\">Account</span>", $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNTARIA->>", " ", $this->headerHTML);
      $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenota", $this->headerHTML);
    }


    $this->footerHTML = file_get_contents("components/footer.html");
  }

  // Methods
  function build_home($paginaHTML)
  {

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_service($paginaHTML)
  {

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_account($paginaHTML)
  {

    //Controllo se l'utente non è autenticato ed eventuale reindirizzamento a login
    if (!(isset($_SESSION["user"]) && $_SESSION["user"] !== null)) {
      header("Location: login.php");
    }

    $page = str_replace("<<-NOME->>", $_SESSION["nameUser"], $paginaHTML);
    $page = str_replace("<<-COGNOME->>", $_SESSION["surnameUser"], $page);
    $page = str_replace("<<-DN->>", $_SESSION["dateUser"], $page);
    $page = str_replace("<<-EMAIL->>", $_SESSION["emailUser"], $page);
    $page = str_replace("<<-USERNAME->>", $_SESSION["usernameUser"], $page);

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $page);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_reservation()
  {
    //Controllo se l'utente non è autenticato ed eventuale reindirizzamento a login
    if (!(isset($_SESSION["user"]) && $_SESSION["user"] !== null)) {
      header("Location: login.php");
    }

    if ($_SESSION["roleUser"] === "Admin") {
      $paginaHTML = require_once '../controller/reservationAdminController.php';
      $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
      $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);
    } else {
      $paginaHTML = file_get_contents("pages/reservationUser.html");
      $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
      $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);
    }
    return $page;
  }

  function build_login()
  {

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
      $HTMLPage = str_replace("<<-HEADER->>", $this->headerHTML, $HTMLPage);
      $HTMLPage = str_replace("<<-FOOTER->>", $this->footerHTML, $HTMLPage);
      return ($HTMLPage);
    }
  }

  function build_register()
  {

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
      $HTMLPage = str_replace("<<-HEADER->>", $this->headerHTML, $HTMLPage);
      $HTMLPage = str_replace("<<-FOOTER->>", $this->footerHTML, $HTMLPage);
      return ($HTMLPage);
    }
  }

  function build_personalReservations($paginaHTML)
  {

    //Controllo se l'utente non è autenticato ed eventuale reindirizzamento a login
    if (!(isset($_SESSION["user"]) && $_SESSION["user"] !== null)) {
      header("Location: login.php");
    }

    $paginaHTML = require '../controller/reservationViewerController.php';

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }
}
