<?php

namespace reservationModel;


require_once "../model/database/database.php";

use DB\DBConn;


class reservationModel
{
  private $db;
  private $connection;

  function __construct()
  {
    $this->db = new DBConn();
    #questa funzione dovrebbe restituire direttamente la connection
    $this->connection = $this->db->openConnection();
  }

  public function createReservation($utente, $numero_campo, $sport, $date, $orario_inizio, $orario_fine)
  {
    $query = "INSERT INTO Prenotazione (utente, numero_campo, tipo_campo, data, ora_inizio, ora_fine) VALUES
(\"$utente\", \"$numero_campo\", \"$sport\", \"$date\", \"$orario_inizio\", \"$orario_fine\")";
    $queryResult = mysqli_query($this->db->conn, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
    return mysqli_affected_rows($this->connection) > 0;
  }
}
