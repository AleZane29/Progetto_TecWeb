<?php

namespace DB;

class DBConn
{
	private const HOST_DB = "localhost";
	private const DATABASE_NAME = "alzanell";
	private const USERNAME = "root";
	private const PASSWORD = "";

	private $connection;

	public function openConnection()
	{
		try {
			$this->connection = mysqli_connect(
				self::HOST_DB,
				self::USERNAME,
				self::PASSWORD,
				self::DATABASE_NAME
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


	public function checkLogin($email, $password)
	{
		$query = "SELECT id, nome, cognome, email, data_nascita
    FROM Utente WHERE email=? AND password=?";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "ss", $email, $password);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		if ($result) {
			$row = mysqli_fetch_assoc($result);
			if ($row) {
				mysqli_stmt_close($stmt);
				return [
					'idUser'      => $row['id'],
					'nameUser'    => $row['nome'],
					'surnameUser' => $row['cognome'],
					'emailUser'   => $row['email'],
					'dateUser'    => $row['data_nascita']
				];
			}
		}

		mysqli_stmt_close($stmt);
		return null;
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

	public function createUser($name, $surname, $email, $birth, $password)
	{
		$query = "INSERT INTO Utente (nome, cognome, email, data_nascita, password) VALUES
(?,?,?,?,?)";
		$stmt = mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($stmt, "sssss", $name, $surname, $email, $birth, $password);
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
		$query = "SELECT * FROM Prenotazione WHERE utente=\"$userId\" ";
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
            P.id AS prenotazione_id,
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
					'id' => $row['prenotazione_id'],
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
		$query = "SELECT nome FROM tipocampo ";
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
		$query = "SELECT numero, tipo FROM campo ";
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
		$query = "UPDATE Prenotazione SET numero_campo = \"$court\", tipo_campo = \"$sport\" , data = \"$data\" , ora_inizio = \"$timeStart\" , ora_fine = \"$timeEnd\", prezzo = \"$price\" WHERE id=\"$id\"";

		mysqli_query($this->connection, $query) or die(mysqli_error($this->connection));
		return mysqli_affected_rows($this->connection) > 0;
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

	/**
     * Recupera le prenotazioni filtrate per sport, campo e data.
     * @param string $sport
     * @param string $campo
     * @param string $data
     * @return array
     */
    public function getSelectedReservations($sport, $campo, $data) {

		$resultArray = array();

        $query = "SELECT * FROM prenotazione WHERE tipo_campo = ? AND numero_campo = ? AND data = ?";

        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param("sss", $sport, $campo, $data);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $resultArray[] = $row;
            }
            $stmt->close();
        }

        return $resultArray;
    }
}
