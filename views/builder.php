<?php

namespace Builder;


class Builder
{
  public $headerHTML;
  public $footerHTML;
  public $personalReservationsLinkHTML;
  public $announcementLinkHTML;

  function __construct()
  {
    if (!isset($_SESSION)) {
      session_start();
    }

    $this->headerHTML = file_get_contents("components/header.html");
    $this->personalReservationsLinkHTML = file_get_contents("components/personalReservationsLink.html");
    $this->announcementLinkHTML = file_get_contents("components/announcementLink.html");

    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      $this->headerHTML = str_replace("<<-ACCOUNT->>", $_SESSION["nameUser"], $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNTARIA->>", $_SESSION["nameUser"], $this->headerHTML);
      if ($_SESSION["roleUser"] === "Admin") {
        $this->headerHTML = str_replace("<<-SPECIFICLINK->>", $this->announcementLinkHTML, $this->headerHTML);
        $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenotazioni", $this->headerHTML);
      } else {
        $this->headerHTML = str_replace("<<-SPECIFICLINK->>", $this->personalReservationsLinkHTML, $this->headerHTML);
        $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenota", $this->headerHTML);
      }
    } else {
      $this->headerHTML = str_replace("<<-SPECIFICLINK->>", ' ', $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNT->>", "<span lang=\"en\">Account</span>", $this->headerHTML);
      $this->headerHTML = str_replace("<<-ACCOUNTARIA->>", " ", $this->headerHTML);
      $this->headerHTML = str_replace("<<-PRENOTA->>", "Prenota", $this->headerHTML);
    }


    $this->footerHTML = file_get_contents("components/footer.html");
  }


  function build_home()
  {

    $paginaHTML = require_once '../controller/announcementController.php';

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_service()
  {
    $paginaHTML = file_get_contents("pages/service.html");

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_account()
  {
    $paginaHTML = file_get_contents("pages/account.html");

    
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


    
    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      header("Location: account.php");
    } else {
      

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


  
    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      header("Location: account.php");
    } else {
      

      if (!$HTMLPage) {
        $HTMLPage = file_get_contents('pages/register.html');
        $HTMLPage = str_replace("[registerResult]", ' ', $HTMLPage);
      }
      $HTMLPage = str_replace("<<-HEADER->>", $this->headerHTML, $HTMLPage);
      $HTMLPage = str_replace("<<-FOOTER->>", $this->footerHTML, $HTMLPage);
      return ($HTMLPage);
    }
  }

  function build_personalReservations()
  {

    $paginaHTML = file_get_contents("pages/personalReservations.html"); 

    
    if (!(isset($_SESSION["user"]) && $_SESSION["user"] !== null)) {
      header("Location: login.php");
    }

    $paginaHTML = require '../controller/reservationViewerController.php';

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_adminAnnouncements()
  {

    $paginaHTML = require '../controller/adminAnnouncementController.php';

    $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }
}
