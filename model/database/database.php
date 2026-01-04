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
		$query = "SELECT * FROM Utente WHERE email=\"$email\" AND password=\"$password\"";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		if ($queryResult) {
			$result = mysqli_fetch_assoc($queryResult);
			if ($result) {
				return array(
					'idUser' => $result['id'],
					'nameUser' => $result['nome'],
					'surnameUser' => $result['cognome'],
					'emailUser' => $result['email'],
					'dateUser' => $result['data_nascita']
				);
			}
		}
		return null;
	}

	public function getUserByEmail($email)
	{
		$query = "SELECT id FROM Utente WHERE email=\"$email\" ";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		if ($queryResult) {
			return mysqli_fetch_assoc($queryResult)['id'];
		}
		return null;
	}

	public function createUser($name, $surname, $email, $birth, $password)
	{
		$query = "INSERT INTO Utente (nome, cognome, email, data_nascita, password) VALUES
(\"$name\", \"$surname\", \"$email\", \"$birth\", \"$password\")";
		$queryResult = mysqli_query($this->connection, $query) or die("Errore in DBAccess" . mysqli_error($this->connection));
		return mysqli_affected_rows($this->connection) > 0;
	}

public function updateUser($id, $name, $surname, $birth)
{
    $name = mysqli_real_escape_string($this->connection, $name);
    $surname = mysqli_real_escape_string($this->connection, $surname);
    $birth = mysqli_real_escape_string($this->connection, $birth);
    $id = mysqli_real_escape_string($this->connection, $id);

    $query = "UPDATE Utente SET 
              nome = '$name', 
              cognome = '$surname', 
              data_nascita = '$birth' 
              WHERE id = '$id'";

    $queryResult = mysqli_query($this->connection, $query);

    if (!$queryResult) {
        die("Errore SQL: " . mysqli_error($this->connection));
    }

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
}
