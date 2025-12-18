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
}
