<?php

namespace App\Core\Model;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Validator\Validator;

/**
 * Class Model
 * @package App\Core\Model
 */
abstract class Model
{

    public $errors = [];

    /**
     * @var mixed
     */
    protected $table;

    /**
     * @var QueryBuilder;
     */
    protected $QB;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $transactionData = [];


    /**
     * Model constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database->getConnection();
        $this->QB = $this->database;
    }


    /**
     * @param int $maxPerPage
     * @param int $currentPage
     * @return \Pagerfanta\Pagerfanta
     */
    public function pagination(int $maxPerPage, int $currentPage, string $order = 'ORDER BY id DESC')
    {
        return $this->QB->paginate($this->table, $maxPerPage, $currentPage, $order);
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->QB->from($this->table)->where('id', $id)->execute()->fetch();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {

        return $this->QB->delete($this->table)->where('id', $id)->execute();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->QB->from($this->table)->execute()->fetchAll();
    }


    /**
     * @param Validator $validator
     * @param array $params
     * @return null
     */
    public function create(Validator $validator, array $params = [])
    {

        $this->hydrate($params);

        $this->QB->insertInto(
            $this->table,
            $this->getTransactionData($params)
        )->execute();
        return null;
    }


    /**
     * @param Validator $validator
     * @param array $params
     * @return null
     */
    public function update(Validator $validator, array $params = [])
    {

        $this->hydrate($params);

        $this->QB->update($this->table)
            ->where('id', $params['id'])
            ->set($this->getTransactionData($params))
            ->execute();
        return null;
    }


    /**
     * @param array $attributes
     */
    protected function hydrate(array $attributes = []): void
    {
        foreach ($attributes as $key => $attribute) {
            $method = 'set' . ucfirst(join('', array_map('ucfirst', explode('_', $key))));
            if (method_exists($this, $method)) {
                $this->$method($attribute);
            }
        }
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function getGuardedAttributes(array $attributes = []): array
    {
        $guarded = [];
        foreach ($attributes as $key => $attribute) {
            $key = lcfirst(join('', array_map('ucfirst', explode('_', $key))));
            if (in_array($key, $this->guarded)) {
                $guarded[$key] = $attribute;
            }
        }
        return $guarded;
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function getTransactionData(array $attributes = []): array
    {
        foreach ($attributes as $key => $attribute) {
            $method = 'get' . ucfirst(join('', array_map('ucfirst', explode('_', $key))));
            if (method_exists($this, $method)) {
                $this->transactionData[$key] = $this->$method($attribute);
            }
        }
        return $this->transactionData;
    }

    /**
     * @return string
     */
    protected function getCurrentDateTime() :string
    {
        return date('Y-m-d H:i:s');
    }
}
