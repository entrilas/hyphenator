<?php

declare(strict_types=1);

namespace App\Core\Database;

class QueryBuilder
{
    private $db = null;
    private string $sql;
    private $stmt;
    private $columnNames;
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

    public function insert(string $table, array $data, array $columns)
    {
        $this->holders = $this->setHolders($data);
        $this->columnNames = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("INSERT INTO $table ($this->columnNames) VALUES ($this->holders)");
        $this->bindParameters($data);
        $this->stmt->execute();
        $this->stmt->debugDumpParams();

    }

    public function select($table, array $columns, string $field, string $param): string|bool
    {
        $this->columnNames = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("SELECT $this->columnNames FROM $table WHERE $field = ?");
        $this->bindParameters(array($param));
        $this->stmt->execute();
        $result = $this->stmt->fetch();
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function selectAll(string $table, array $columns): string|bool
    {
        $this->columnNames = $this->setColumns($columns);
        $this->stmt = $this->db->prepare("SELECT $this->columnNames FROM $table");
        $this->stmt->execute();
        $result = $this->stmt->fetchAll();
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function update(string $table, array $columns, array $data, string $param): string|bool
    {
        $this->fields = $this->setFields($columns);
        $this->stmt = $this->db->prepare("UPDATE $table SET $this->fields WHERE $param = ?");
        $this->bindParameters(array($data));
        return $this->stmt->execute();
    }

    public function delete(string $table, array $data, string $param): string|bool
    {
        $this->stmt = $this->db->prepare("DELETE FROM $table WHERE $param = ?");
        $this->bindParameters(array($data));
        return $this->stmt->execute();
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

    private function bindParameters(array $params): void
    {
        for($i=0;$i<sizeof($params);$i++)
        {
            $this->stmt->bindValue($i+1, $params[$i]);
        }
    }
}


