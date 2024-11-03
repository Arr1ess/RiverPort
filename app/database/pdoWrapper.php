<?php

class pdoWrapper
{
	private static $instance = null;
	private $connection = null;

	public static $dbname;

	private function __construct()
	{
		$user = '';
		$password = '';
		$db = '';
		$host = '';

		pdoWrapper::$dbname = $db;

		try {
			$this->connection = new PDO("mysql:dbname=$db;host=$host;charset=utf8mb4", $user, $password);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new RuntimeException("Database connection failed: " . $e->getMessage());
		}
	}

	private function __clone() {}

	public function __wakeup()
	{
		throw new BadMethodCallException("Unable to deserialize");
	}

	public static function getInstance(): pdoWrapper
	{
		if (null === self::$instance) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	public static function connection(): PDO
	{
		return static::getInstance()->getConnection();
	}

	public static function prepare($statement): PDOStatement
	{
		return static::connection()->prepare($statement);
	}

	public static function query($query)
	{
		return static::connection()->query($query);
	}

	public static function lastInsertId(): int
	{
		return intval(static::connection()->lastInsertId());
	}

	public static function beginTransaction(): bool
	{
		return static::connection()->beginTransaction();
	}

	public static function commit(): bool
	{
		return static::connection()->commit();
	}

	public static function rollback(): bool
	{
		return static::connection()->rollback();
	}

	public static function executePrepared(PDOStatement $stmt, array $params = []): bool
	{
		try {
			return $stmt->execute($params);
		} catch (PDOException $e) {
			throw new RuntimeException("Error executing prepared statement: " . $e->getMessage());
		}
	}

	public static function fetchAll(PDOStatement $stmt): array
	{
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function fetch(PDOStatement $stmt)
	{
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public static function countRows(PDOStatement $stmt): int
	{
		return $stmt->rowCount();
	}

	private function getConnection(): PDO
	{
		return $this->connection;
	}
}
