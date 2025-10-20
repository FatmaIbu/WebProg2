<?php
require_once __DIR__ . "/../config.php";

abstract class BaseDao {
    protected $connection;
    protected $table_name;

    public function __construct($table_name) {
        $this->table_name = $table_name;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function get_all() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_by_id($id, $id_column = "id") {
        $query = "SELECT * FROM " . $this->table_name . " WHERE $id_column = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function add($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
        $query = "INSERT INTO " . $this->table_name . " ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute(array_values($data));
        
        return $this->connection->lastInsertId();
    }

    public function update($id, $data, $id_column = "id") {
        $setClause = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        
        $query = "UPDATE " . $this->table_name . " SET $setClause WHERE $id_column = ?";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id, $id_column = "id") {
        $query = "DELETE FROM " . $this->table_name . " WHERE $id_column = ?";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$id]);
    }

    public function count_all() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}