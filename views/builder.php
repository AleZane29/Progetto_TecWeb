<?php

namespace Builder;


class Builder
{
  // Properties
  public $headerHTML;
  public $footerHTML;

  function __construct()
  {

    $headerHTML = file_get_contents("components/header.html");
    if (isset($_SESSION["user"]) && $_SESSION["user"] !== null) {
      $this->$headerHTML = str_replace("<<-BENVENUTO->>", $_SESSION["user"] , $headerHTML);
    }else{
      $this->headerHTML = str_replace("<<-BENVENUTO->>", "non loggato" , $headerHTML);
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
  
}
