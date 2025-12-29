<?php

namespace reservationModel;


require_once "../model/database/database.php";
use DB\DBConn;


class reservationModel
{
private $db;
private $connessione;

  function __construct()
  {
    $this->db = new DBConn();
    #questa funzione dovrebbe restituire direttamente la connessione
    $this->connessione = $this->db->openConnection();
  }

  public function createReservation($utente, $numero_campo, $sport, $date, $orario_inizio, $orario_fine)
	{
		$query = "INSERT INTO Prenotazione (utente, numero_campo, tipo_campo,data_, ora_inizio, ora_fine) VALUES
(\"$utente\", \"$numero_campo\", \"$sport\", \"$date\", \"$orario_inizio\", \"$orario_fine\")";
		$queryResult = mysqli_query($this->connessione, $query) or die("Errore in DBAccess" . mysqli_error($this->connessione));
		return mysqli_affected_rows($this->connessione) > 0;

	}


}


?>