<?php
require_once __DIR__ . '/../Database.php';

class BaseDao {
    protected $conn;
    protected $table;

    public function __construct($table) {
        $this->conn = Database::connect();
        $this->table = $table;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function insert($data) {
        $keys = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} ({$keys}) VALUES ({$placeholders})");
        $stmt->execute($data);
        $data['id'] = $this->conn->lastInsertId();
        return $data;
    }

    public function update($id, $data) {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');
        $data['id'] = $id;
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET {$fields} WHERE id = :id");
        $stmt->execute($data);
        return $data;
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return ['status' => 'success'];
    }

    public function query($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function query_unique($query, $params = []) {
        $results = $this->query($query, $params);
        return count($results) > 0 ? $results[0] : null;
    }
}
