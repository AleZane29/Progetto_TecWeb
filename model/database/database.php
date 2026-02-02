<?php

namespace DB;

class DBConn
{

	private $connection;

	public function openConnection()
	{
		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_name = "alzanell"; 

		if (getenv('AM_I_IN_DOCKER')) {
			$db_host = "db";            
			$db_user = "user";          
			$db_pass = "password";      
			$db_name = "alzanell";      
		}

		try {
			$this->connection = mysqli_connect(
				$db_host,
				$db_user,
				$db_pass,
				$db_name
			);

			if (!$this->connection) {
				throw new \mysqli_sql_exception("Failed to connect to MySQL: " . mysqli_connect_error());
			}
			return true;
		} catch (\mysqli_sql_exception $e) {
			echo "Connection failed: " . $e->getMessage();
			return false;
		}
	}

	public function closeConnection()
	{
		try {
			if ($this->connection) {
				mysqli_close($this->connection);
				$this->connection = null;
				return true;
			} else {
				throw new \Exception("Connection is not established.");
			}
		} catch (\Exception $e) {
			echo "Error while closing connection: " . $e->getMessage();
			return false;
		}
	}

	public function getUserByEmail($email)
	{
		$query = "SELECT * FROM Utente WHERE email=? ";

		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);
		$found = mysqli_fetch_assoc($result);

		mysqli_stmt_close($stmt);
		return $found;
	}

	public function getUserByUsername($username)
	{
		$query = "SELECT * FROM Utente WHERE username=? ";

		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);
		$found = mysqli_fetch_assoc($result);

		mysqli_stmt_close($stmt);
		return $found;
	}

	public function createUser($name, $surname, $username, $email, $birth, $password)
	{
		$query = "INSERT INTO Utente (nome, cognome, username, email, data_nascita, password) VALUES (?,?,?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "ssssss", $name, $surname, $username, $email, $birth, $password);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) < 0) {
			mysqli_stmt_close($stmt);
			die("Errore SQL");
		}

		mysqli_stmt_close($stmt);
		return true;
	}

	public function updateUser($id, $name, $surname, $birth)
	{
		$query = "
    	UPDATE Utente 
    	SET nome = ?, cognome = ?, data_nascita = ?
    	WHERE id = ?
		";

		$stmt = mysqli_prepare($this->connection, $query);

		mysqli_stmt_bind_param(
			$stmt,
			"sssi",
			$name,
			$surname,
			$birth,
			$id
		);

		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) < 0) {
			mysqli_stmt_close($stmt);
			die("Errore SQL");
		}

		mysqli_stmt_close($stmt);
		return true;
	}

	public function getUserReservations($userId)
	{
		$query = "SELECT * FROM Prenotazione WHERE utente=\"$userId\" ORDER BY DATA DESC ";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		if (mysqli_num_rows($queryResult) != 0) {
			$result = array();
			while ($row = mysqli_fetch_assoc($queryResult)) {
				$result[] = array(
					'id' => $row['id'],
					'utente' => $row['utente'],
					'numero_campo' => $row['numero_campo'],
					'tipo_campo' => $row['tipo_campo'],
					'data' => $row['data'],
					'ora_inizio' => substr($row['ora_inizio'], 0, 5),
					'ora_fine' => substr($row['ora_fine'], 0, 5),
					'prezzo' => $row['prezzo']
				);
			}
			mysqli_free_result($queryResult);
			return $result;
		}
	}

	public function getAllReservations()
	{
		$query = "
        SELECT 
            P.id AS Prenotazione_id,
            P.numero_campo,
            P.tipo_campo,
            P.data,
            P.ora_inizio,
            P.ora_fine,
            P.prezzo,
            U.nome AS nome_utente,
            U.cognome AS cognome_utente
        FROM Prenotazione AS P
        INNER JOIN Utente AS U ON P.utente = U.id
				ORDER BY P.DATA DESC
    	";

		$queryResult = mysqli_query($this->connection, $query)
			or die("Errore in DBAccess: " . mysqli_error($this->connection));

		if (mysqli_num_rows($queryResult) > 0) {
			$result = array();

			while ($row = mysqli_fetch_assoc($queryResult)) {
				$result[] = array(
					'id' => $row['Prenotazione_id'],
					'utente' => $row['nome_utente'] . ' ' . $row['cognome_utente'],
					'numero_campo' => $row['numero_campo'],
					'tipo_campo' => $row['tipo_campo'],
					'data' => $row['data'],
					'ora_inizio' => substr($row['ora_inizio'], 0, 5),
					'ora_fine' => substr($row['ora_fine'], 0, 5),
					'prezzo' => $row['prezzo']
				);
			}

			mysqli_free_result($queryResult);
			return $result;
		}

		return [];
	}

	public function removePrenotation($id)
	{
		$query = "DELETE FROM Prenotazione WHERE id=\"$id\"";

		mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));

		return mysqli_affected_rows($this->connection) > 0;
	}

	public function getAllSports()
	{
		$query = "SELECT nome FROM TipoCampo ";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		if (mysqli_num_rows($queryResult) != 0) {
			$result = array();
			while ($row = mysqli_fetch_assoc($queryResult)) {
				$result[] = array(
					'nome' => $row['nome']
				);
			}
			mysqli_free_result($queryResult);
			return $result;
		}
	}

	public function getAllCourts()
	{
		$query = "SELECT numero, tipo FROM Campo ";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		if (mysqli_num_rows($queryResult) != 0) {
			$result = array();
			while ($row = mysqli_fetch_assoc($queryResult)) {
				$result[] = array(
					'numero' => $row['numero'],
					'tipo' => $row['tipo']
				);
			}
			mysqli_free_result($queryResult);
			return $result;
		}
	}

	public function getPriceSport($sport)
	{
		$query = "SELECT costo_orario FROM TipoCampo WHERE nome=? ";

		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "s", $sport);
		mysqli_stmt_execute($stmt);

		mysqli_stmt_bind_result($stmt, $costo);
		$found = mysqli_stmt_fetch($stmt);

		mysqli_stmt_close($stmt);
		return $found ? $costo : null;
	}

	public function updateReservation($id, $sport, $court, $data, $timeStart, $timeEnd, $price)
	{
		$query = "UPDATE Prenotazione SET 
				numero_campo = \"$court\", 
				tipo_campo = \"$sport\", 
				data = \"$data\", 
				ora_inizio = \"$timeStart\", 
				ora_fine = \"$timeEnd\", 
				prezzo = \"$price\" 
				WHERE id=\"$id\"";

		// Eseguiamo la query
		$result = mysqli_query($this->connection, $query);

		// Se $result è FALSE, significa che c'è stato un errore SQL grave (sintassi, connessione persa, etc.)
		if (!$result) {

			return false;
		}

		// Ritorniamo TRUE anche se le righe modificate sono 0 (nessun cambiamento ai dati).
		return true;
	}


	public function createReservation($userId, $sport, $court, $data, $timeStart, $timeEnd, $price)
	{
		$query = "INSERT INTO Prenotazione (utente, numero_campo, tipo_campo, data, ora_inizio, ora_fine, prezzo) 
		VALUES (?,?,?,?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "issssss", $userId, $court, $sport, $data, $timeStart, $timeEnd, $price);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) < 0) {
			mysqli_stmt_close($stmt);
			die("Errore SQL");
		}

		mysqli_stmt_close($stmt);
		return true;
	}

	public function getSelectedReservations($sport, $campo, $data, $excludeId = null)
	{

		$resultArray = array();

		$query = "SELECT * FROM Prenotazione WHERE tipo_campo = ? AND numero_campo = ? AND data = ?";

		if ($excludeId) {
			$query .= " AND id != ?";
		}

		if ($stmt = $this->connection->prepare($query)) {
			if ($excludeId) {
				$stmt->bind_param("sssi", $sport, $campo, $data, $excludeId);
			} else {
				$stmt->bind_param("sss", $sport, $campo, $data);
			}
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$resultArray[] = $row;
			}
			$stmt->close();
		}

		return $resultArray;
	}

	public function checkOverlap($sport, $court, $date, $startTime, $endTime, $excludeId = null)
	{
		// Cerca prenotazioni nello stesso campo e stessa data
		// che iniziano prima che la nuova finisca e finiscono dopo che la nuova inizi.
		$query = "SELECT COUNT(*) as total FROM Prenotazione 
                  WHERE tipo_campo = ?
				  AND numero_campo = ? 
                  AND data = ? 
                  AND (ora_inizio < ? AND ora_fine > ?)";

		if ($excludeId) {
			$query .= " AND id != ?";
		}

		$stmt = mysqli_prepare($this->connection, $query);

		if ($excludeId) {
			mysqli_stmt_bind_param($stmt, "sssssi", $sport, $court, $date, $endTime, $startTime, $excludeId);
		} else {
			mysqli_stmt_bind_param($stmt, "sssss", $sport, $court, $date, $endTime, $startTime);
		}

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);

		mysqli_stmt_close($stmt);

		return $row['total'] > 0;
	}


	public function getReservationById($id)
	{
		$query = "SELECT * FROM Prenotazione WHERE id = ?";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "i", $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);
		return $row;
	}

	public function getAllAnnouncements()
	{
		$resultArray = array();

		$query = "SELECT *, DATE(data) AS data FROM Annunci";

		if ($stmt = $this->connection->prepare($query)) {

			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				$resultArray[] = $row;
			}
			$stmt->close();
		}

		return $resultArray;
	}

	public function howManyAnnouncements()
	{
		$query = "SELECT COUNT(*) as total FROM Annunci";

		$stmt = mysqli_prepare($this->connection, $query);

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);

		mysqli_stmt_close($stmt);

		return $row['total'];
	}
	
	public function createAnnouncement($id, $title, $description)
	{
		if($this->howManyAnnouncements() >= 5){
			return false;
		}

		$query = "INSERT INTO Annunci (idAdmin, titolo, descrizione) VALUES (?, ?, ?)";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "iss", $id, $title, $description);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) < 0) {
			mysqli_stmt_close($stmt);
			die("Errore SQL");
		}

		mysqli_stmt_close($stmt);
		return true;
	}

	
	public function removeAnnouncement($id)
	{
		$query = "DELETE FROM Annunci WHERE id = ?";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "i", $id);
		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_affected_rows($stmt) < 0) {
			mysqli_stmt_close($stmt);
			die("Errore SQL");
		}

		mysqli_stmt_close($stmt);
		return true;
	}

	public function editAnnouncement($id, $title, $description)
	{
		$query = "UPDATE Annunci SET titolo = ?, descrizione = ? WHERE id = ?";
		
		$stmt = mysqli_prepare($this->connection, $query);
			mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $id);
		
		if (mysqli_stmt_execute($stmt)) {
			$successo = true;
		} else {
			mysqli_stmt_close($stmt);
			die("Errore SQL: " . mysqli_error($this->connection));
		}

		mysqli_stmt_close($stmt);
		return $successo;
	}
}
