<?php

//Para versao >7.4
//use PDOStatement;


class c_banco_pdo
{

    public $conn;
    
    
    //Versao para php <7.4
    /** @var \PDOStatement */
    public $stmt;

	public function __construct()
	{
		$this->open_connection();
	}

	public function open_connection()
	{
		try {
			$this->conn = new PDO('mysql:host=' . HOSTNAME . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
				[
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
				]
			);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new Exception("Não foi possível conectar ao banco de dados: " . $e->getMessage());
		}
	}

	public function prepare($sql)
	{
		$this->stmt = $this->conn->prepare($sql);
	}

	public function execute($params = null)
	{
		if (!$this->stmt) {
			throw new Exception("Nenhuma consulta preparada.");
		}

		if($params !== null and $params !== "" ){
			$this->stmt->execute($params);
		} else {
			$this->stmt->execute();
		}
		
		return $this->stmt;
	}

	public function fetchAll()
	{
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function fetch()
	{
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function rowCount()
	{
		return $this->stmt->rowCount();
	}

	public function queryString()
	{
		return $this->stmt->queryString;
	}

	public function bindValue($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	public function bindParam($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindParam($param, $value, $type);
	}

	public function lastInsertId()
	{
		return $this->conn->lastInsertId();
	}

	public function setConnection($connection)
	{
		$this->conn = $connection;
	}

	public function beginTransaction(): bool
	{
		return $this->conn->beginTransaction();
	}

	public function commit(): bool
	{
		return $this->conn->commit();
	}

	public function rollBack(): bool
	{
		return $this->conn->rollBack();
	}

	public function close()
	{
		$this->conn = null;
	}

	public function errorInfo()
	{
		return $this->stmt->errorInfo();
	}

	/**
	 * @name dateToMysql
	 * @description Converte data no formato dd/mm/yyyy para o formato MySQL (yyyy-mm-dd)
	 *
	 * @param  string  $date   Data no formato dd/mm/yyyy
	 * @return string|null     Data no formato yyyy-mm-dd ou null se inválida
	 */
	public function dateToMysql(?string $date = null): ?string
	{
		if ($date === null || $date === '') {
			return null;
		}

		$d = DateTime::createFromFormat('d/m/Y', $date);

		if (!$d || $d->format('d/m/Y') !== $date) {
			return null;
		}

		return $d->format('Y-m-d');
	}


}
