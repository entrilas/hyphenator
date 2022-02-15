<?php

namespace App\Core\Database;

class QueryBuilder
{
//    private const OPERATORS = ['=', '>', '<', '>=', '<=', '!='];
//    private const LOGICS = ['AND', 'OR'];
    private $database;
    private $query;
    private bool $error = false;

    private static $instance;

    public function __construct()
    {
        $this->database = Database::getInstanceOf()->getConnector();
    }

    public static function getInstanceOf(): QueryBuilder
    {
        if (!self::$instance) {
            self::$instance = new QueryBuilder();
        }
        return self::$instance;
    }

    /**
     * @return bool
     */
    public function getError(): bool
    {
        return $this->error;
    }

    public function query(string $sql, array $params = []): QueryBuilder {
        $this->error = false;
        $this->query = $this->database->prepare($sql);

        if(count($params)) {
            $i = 1;
            foreach($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }

        if(!$this->query->execute()) {
            $this->error = true;
        }
        return $this;
    }

    public function insert(string $table, array $fields = []): bool
    {
        $values = '';
        foreach ($fields as $field) {
            $values .= "?,";
        }
        $val = rtrim($values, ',');

        $sql = "INSERT INTO `$table` (" . '`' . implode('`, `', array_keys($fields)) . '`' . ") VALUES ($val)";
        if ($this->query($sql, $fields)->getError()){
            return false;
        }
        return true;
    }

    public function action(string $action, string $table, array $where = [], string $addition = '')
    {
        if (empty($where)) {
            $sql = "$action FROM `$table` $addition";
            if (!$this->query($sql)->getError()){
                return $this;
            }
        }

        $condition = $this->prepareCondition($where);

        $sql = "$action FROM `$table` WHERE {$condition['sql']} $addition";
        if(!$this->query($sql, $condition['values'])->getError()){
            return $this;
        }
        return false;
    }

    public function get(string $table, array $where = [], string $addition = '')
    {
        return $this->action('SELECT *', $table, $where, $addition);
    }

    public function getAll(string $table, string $addition = '')
    {
        return $this->action('SELECT *', $table, [], $addition);
    }

    public function truncate($table): bool
    {
        $sql = "DELETE FROM $table";
        if ($this->query($sql)->getError()){
            return false;
        }
        return true;
    }


}