<?php

namespace Builder;


class Builder
{
  // Properties
  public $headerHTML;
  public $footerHTML;

  function __construct()
  {
    if (!isset($_SESSION)) {
      session_start();
    }

    $this->headerHTML = file_get_contents("components/header.html");
    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      $this->headerHTML = str_replace("<<-BENVENUTO->>", $_SESSION["user"], $this->headerHTML);
    } else {
      $this->headerHTML = str_replace("<<-BENVENUTO->>", "non loggato", $this->headerHTML);
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
    $page = str_replace("<<-HEADER->>", $this->headerHTML, $page);
    $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);

    return $page;
  }

  function build_reservation(){

    if($_SESSION["user"]=="2"){
      $paginaHTML = file_get_contents("pages/reservationAdmin.html");
      $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
      $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);
    }else{
      $paginaHTML = file_get_contents("pages/reservationUser.html");
      $page = str_replace("<<-HEADER->>", $this->headerHTML, $paginaHTML);
      $page = str_replace("<<-FOOTER->>", $this->footerHTML, $page);
    }
    return $page;
  }







}
