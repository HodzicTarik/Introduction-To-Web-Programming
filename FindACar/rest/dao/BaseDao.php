<?php
require_once __DIR__ . '/../config.php';

class BaseDao {
    protected $table;
    protected $connection;

    public function __construct($table) {
        $this->table = $table;
        $this->connection = getDatabaseConnection();

        // Safety check: verify table exists
        $tables = $this->connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array($this->table, $tables)) {
            die(json_encode(['error' => "Tabela '{$this->table}' ne postoji u bazi. Dostupne su: " . implode(', ', $tables)]));
        }
    }

    public function getAll() {
        try {
            $stmt = $this->connection->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: ['error' => "Record with ID $id not found"];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function insert($data) {
        if (empty($data)) return ['error' => 'Insert data is empty'];
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($data);
            return ['id' => $this->connection->lastInsertId()];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function update($id, $data) {
        if (empty($data)) return ['error' => 'Update data is empty'];
        try {
            $fields = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $data['id'] = $id;
            $stmt->execute($data);
            return ['updated' => $stmt->rowCount() > 0];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return ['deleted' => $stmt->rowCount() > 0];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
?>
