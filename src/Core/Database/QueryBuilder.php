<?php

namespace App\Core\Database;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Class QueryBuilder
 * @package App\Core\Database
 */
class QueryBuilder
{

    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var array
     */
    private $sql;
    /**
     * @var array|string|null
     */
    private $select;
    /**
     * @var array|null
     */
    private $from;
    /**
     * @var array
     */
    private $where = [];
    /**
     * @var string|null
     */
    private $count;
    /**
     * @var array
     */
    private $params;
    /**
     * @var string
     */
    private $order;
    /**
     * @var int;
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;

    /**
     * QueryBuilder constructor.
     * @param PDO $pdo
     */
    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo;
    }

    /**
     * @param string[] ...$attr
     * @return QueryBuilder
     */
    public function select(string ...$attr): self {
        if(empty($attr)) {
            $this->select = '';
        } else {
            $this->select = $attr;
        }
        return $this;
    }

    /**
     * @param string $table
     * @param null|string $alias
     * @return QueryBuilder
     */
    public function from(string $table, ?string $alias = null): self {
        if($alias) {
            $this->from[$alias] = $table;
        } else {
            $this->from[] = $table;
        }
        return $this;
    }

    /**
     * @param string[] ...$terms
     * @return QueryBuilder
     */
    public function where(string ...$terms): self {
        $this->where = array_merge($this->where, $terms);
        return $this;
    }

    /**
     * @param array $params
     * @return QueryBuilder
     */
    public function params(array $params): self {
        $this->params = $params;
        return $this;
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return QueryBuilder
     */
    public function limit(int $limit = 10, ?int $offset = null): self {
        $this->limit = $limit;
        if(!is_null($offset)) {
            $this->offset = $offset;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int {
        $this->select("COUNT(id)");
        return $this->execute()->fetchColumn();
    }

    /**
     * @param string $table
     * @param array $params
     * @return QueryBuilder
     */
      public function update(string $table, array $params): self {
        $this->select = "UPDATE {$table}";

        $this->params = "(" . implode(', ', array_keys($params)) . ") SET (:" . implode(', :',array_keys($params)) . ")";

        return $this;
    }

    /**
     * @param string $table
     * @param array $params
     * @return QueryBuilder
     */
      public function insert(string $table, array $params): self {
        $this->type = "INSERT INTO";
        $this->table = $table;

        $attributes = [];
        foreach ($params as $key => $value) {
            if(is_int($value) || is_float($value) || is_bool($value)) {
                $attributes['int'][$key] = $value;
            } else {
                $attributes['str'][$key] = "'$value'";
            }
        }

        if(!empty($attributes['int']) && !empty($attributes['str'])) {
            $params = implode(', ', array_keys($attributes['int'])) . ', ' . implode(', ', array_keys($attributes['str']));
            $values = implode(', ', array_values($attributes['int'])) . ', ' . implode(', ', array_values($attributes['str']));
            $this->params = "({$params}) VALUES ({$values})";
        } elseif (!empty($attributes['int'])) {
            $params = implode(', ', array_keys($attributes['int']));
            $values = implode(', ', array_values($attributes['int']));
            $this->params = "({$params}) VALUES ({$values})";
        } elseif (!empty($attributes['str'])) {
            $params = implode(', ', array_keys($attributes['str']));
            $values = implode(', ', array_values($attributes['str']));
            $this->params = "({$params}) VALUES ({$values})";
        }

        return $this;
    }


    /**
     * @param string $filter
     * @param string[] ...$fields
     * @return QueryBuilder
     */
    public function order(string $filter, string ...$fields): self {
        if($filter === 'ASC' || $filter === 'DESC') {
            $fields = trim(implode(', ', $fields));
            $this->order = "ORDER BY " . $fields . ' ' . $filter;
        }
        return $this;
    }

    /**
     * @return string
     */
    private function buildSelect(): string {
        if(is_null($this->select)){
            return '';
        }
        if(is_string($this->select)) {
            return 'SELECT *';
        } else {
            return 'SELECT ' . join(', ', $this->select);
        }
    }

    /**
     * @return string
     */
    private function buildCount(): string {
        if(is_null($this->count)){
            return '';
        }
        return "SELECT COUNT({$this->count})";
    }

    /**
     * @return string
     */
    private function buildFrom(): string {
        if(is_null($this->from)){
            return '';
        }

        $from = [];
        foreach ($this->from as $key => $value) {
           if(is_int($key)){
                $from[] = $value;
           } else {
                $from[] = "{$value} AS {$key}";
           }
        }
        return 'FROM ' . join(', ', $from);
    }

    /**
     * @return string
     */
    private function buildWhere(): string {
        if(empty($this->where)){
            return '';
        }

        return 'WHERE (' . join(') AND (', $this->where) . ')';
    }

    /**
     * @return string
     */
    private function buildOrder(): string {
        if(is_null($this->order)){
            return '';
        }

        return $this->order;
    }

    /**
     * @return string
     */
    private function buildLimit(): string {
        if(is_null($this->limit)){
            return '';
        }

        if(is_null($this->offset)) {
            return "LIMIT {$this->limit}";
        }
        return "LIMIT {$this->limit},";
    }

    /**
     * @return string
     */
    private function buildOffset(): string {
        if(is_null($this->limit) || is_null($this->offset)){
            return '';
        }

        return $this->offset;
    }

    /**
     * @return bool|PDOStatement
     * @throws SQLException
     */
    public function execute() {
        $query = $this->__toString();

        $this->queryCleaner();

        try {

            if(!empty($this->params)) { // Query Prepared
                $statement = $this->bindParams($query);
                return $statement->execute($this->params);
            }

            return $this->pdo->query($query); // Query Simple

        } catch (PDOException $e) {
            throw new SQLException('This query is not correct' .$e->getMessage());
        }

    }


    /**
     * Add parameters for the query
     * @param $params
     * @return PDOStatement
     */
    private function bindParams($params) :PDOStatement {
        return $this->pdo->prepare($params);
    }

    /**
     * Clear the query
     */
    private function queryCleaner() {
        $this->sql = '';
    }

    /**
     * @return string
     */
    public function __toString() {
        $this->sql = [];
        $this->sql[] = $this->buildSelect(); // SELECT
        $this->sql[] = $this->buildCount();  // COUNT
        $this->sql[] = $this->buildFrom();   // FROM
        $this->sql[] = $this->buildWhere();  // WHERE
        $this->sql[] = $this->buildOrder();  // ORDER
        $this->sql[] = $this->buildLimit();  // LIMIT
        $this->sql[] = $this->buildOffset(); // OFFSET
        $this->sql = (string)join(' ', array_filter($this->sql));

        return (string)$this->sql;
    }


}

