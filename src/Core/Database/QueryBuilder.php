<?php

namespace App\Core\Database;

use Pagerfanta\Adapter\AdapterInterface;
use PDO;
use Pagerfanta\Pagerfanta;
use PHPUnit\Runner\Exception;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class QueryBuilder
 * @package App\Core\Database
 */
class QueryBuilder extends \FluentPDO implements AdapterInterface
{

    /**
     * @var PDO
     */
    protected $pdo;
    /**
     * @var null|string
     */
    private $paginateQuery;
    /**
     * @var null|string
     */
    private $countPaginationQuery;


    public function __construct(?PDO $pdo = null, ?string $paginateQuery = null, ?string $countPaginationQuery = null)
    {
        $this->pdo = $pdo;
        $this->paginateQuery = $paginateQuery;
        $this->countPaginationQuery = $countPaginationQuery;
    }



    public function paginate(string $table, int $maxPerPage, int $page) : Pagerfanta
    {
        return (
            new Pagerfanta(
                (new $this($this->pdo, "SELECT * FROM {$table}", "SELECT COUNT(id) FROM {$table}"))
            )
        )->setMaxPerPage($maxPerPage)->setCurrentPage($page);
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        return $this->pdo->query($this->countPaginationQuery)->fetchColumn();
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length)
    {
        $statement =  $this->pdo->prepare($this->paginateQuery . " LIMIT :offset , :length");
        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
