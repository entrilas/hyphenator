<?php

declare(strict_types=1);

namespace App\Core\Database;

class QueryBuilder
{
    private $db = null;
    private $stmt;
    private $cols;
    private $holders;
    private $fields;

    public function __construct(
        private Database $database
    ) {
        $this->receiveHandle();
    }

    private function receiveHandle()
    {
        $this->db = $this->database->getConnector();
    }

    public function insert(string $table, array $data, array $columns): string|bool
    {
        $this->holders = $this->setHolders($columns);
        $this->cols = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("INSERT INTO $table ($this->cols) VALUES ($this->holders)");
        return $this->stmt->execute($data);
    }

    public function select($table, array $columns, $field, $param): string|bool
    {
        $this->cols = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("SELECT $this->cols FROM $table WHERE $field = ?");
        $this->stmt->execute(array($param));
        $result = $this->stmt->fetch();
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function selectAll($table, array $columns): string|bool
    {
        $this->cols = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("SELECT $this->cols FROM $table");
        $this->stmt->execute();
        $result = $this->stmt->fetchAll();
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function update(string $table, array $columns, array $data, string $param): string|bool
    {
        $this->fields = $this->setFields($columns);
        $this->stmt = $this->db->prepare("UPDATE $table SET $this->fields WHERE $param = ?");
        return $this->stmt->execute($data);
    }

    public function delete(string $table, array $data, string $param): string|bool
    {
        $this->stmt = $this->db->prepare("DELETE FROM $table WHERE $param = ?");
        return $this->stmt->execute($data);
    }

    public function truncate(string $table): string|bool
    {
        $this->stmt = $this->db->prepare("DELETE FROM $table");
        return $this->stmt->execute();
    }

    private function setColumns(array $columns): string
    {
        $this->cols = implode(', ', array_values($columns));
        return $this->cols;
    }

    private function setFields(array $columns): string
    {
        $this->fields = implode(' = `?`, ', array_values($columns));
        return $this->fields.' = ?';
    }

    private function setHolders(array $columns): string
    {
        $this->holders = array_fill(1 ,count($columns),'?');
        return implode(', ',array_values($this->holders));
    }
}


