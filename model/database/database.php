<?php

namespace Model;

class Database
{
	private const HOST_DB = "localhost";
	private const DATABASE_NAME = "lpasqual";
	private const USERNAME = "lpasqual";
	private const PASSWORD = "ohSh3thipo7toavi";

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
}
