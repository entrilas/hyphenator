<?php

declare(strict_types=1);

namespace App\Core\Database;

class QueryBuilder
{
    private $db = null;
    private string $query;
    private string $table;
    private $stmt;

    public function __construct(
        private Database $database
    ) {
        $this->receiveHandle();
        $this->query = '';
    }

    private function receiveHandle()
    {
        $this->db = $this->database->getConnector();
    }

    public function table(string $table): QueryBuilder
    {
        $this->table = $table;
        return $this;
    }

    public function insert(): QueryBuilder
    {
        $this->resetQuery();
        $this->query .= "INSERT INTO $this->table";
        return $this;
    }

    public function select(array $columns): QueryBuilder
    {
        $columnNames = $this->setColumns($columns);
        $this->resetQuery();
        $this->query .= "SELECT $columnNames";
        return $this;
    }

    public function update(): QueryBuilder
    {
        $this->resetQuery();
        $this->query .= "UPDATE $this->table";
        return $this;
    }

    public function delete(): QueryBuilder
    {
        $this->resetQuery();
        $this->query .= "DELETE";
        return $this;
    }

    public function columns(array $columns): QueryBuilder
    {
        $columnNames = $this->setColumns($columns);
        $this->query .= " ($columnNames)";
        return $this;
    }

    public function values(array $data): QueryBuilder
    {
        $valuesHolders = $this->setHolders($data);
        $this->query .= " VALUES ($valuesHolders)";
        return $this;
    }

    public function from(): QueryBuilder
    {
        $this->query .= " FROM $this->table";
        return $this;
    }

    public function where(string $field): QueryBuilder
    {
        $this->query .= " WHERE $field = ?";
        return $this;
    }

    public function set(array $columns): QueryBuilder
    {
        $columnFields = $this->setFields($columns);
        $this->query .= " SET $columnFields";
        return $this;
    }

    public function resetQuery(): QueryBuilder
    {
        $this->query = '';
        return $this;
    }

    public function execute($data = null): QueryBuilder
    {
        $this->stmt = $this->db->prepare($this->query);
        $this->bindParameters($data);
        $this->stmt->execute();
        return $this;
    }

    public function getData(): array|bool
    {
        $fetchedData = $this->stmt->fetch();
        if($fetchedData === false)
            return false;
        return (array)$fetchedData;
    }

    public function getAllData(): array|bool
    {
        $fetchedData = $this->stmt->fetchAll();
        if($fetchedData === false)
            return false;
        return (array)$fetchedData;
    }

    public function truncate(string $table): string|bool
    {
        $this->stmt = $this->db->prepare("DELETE FROM $table");
        return $this->stmt->execute();
    }

    private function setColumns(array $columns): string
    {
        return implode(', ', array_values($columns));
    }

    private function setFields(array $columns): string
    {
        $columnFieldsArray = implode(' = ?, ', array_values($columns));
        return sprintf("%s = ?", $columnFieldsArray);
    }

    private function setHolders(array $columns): string
    {
        $valuesHoldersArray = array_fill(1 ,count($columns), '?');
        return implode(', ',array_values($valuesHoldersArray));
    }

    private function bindParameters(array $params = null): void
    {
        if($params != null){
            for($i=0;$i<sizeof($params);$i++)
            {
                $this->stmt->bindValue($i+1, $params[$i]);
            }
        }
    }
}
