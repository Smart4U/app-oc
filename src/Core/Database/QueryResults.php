<?php

namespace App\Core\Database;

use App\Core\Helper\Hydrator;
use Exception;
use Iterator;
use ArrayAccess;

/**
 * Class QueryResults
 * @package App\Core\Database
 */
class QueryResults implements Iterator, ArrayAccess
{
    /**
     * @var null|string
     */
    public $model;

    /**
     * @var array
     */
    public $records;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var array
     */
    private $hydratedRecords = [];

    /**
     * QueryResults constructor.
     * @param array $records
     * @param null|string $model
     */
    public function __construct(array $records, ?string $model = null)
    {
        $this->records = $records;
        $this->model = $model;
    }

    /**
     * @param int $index
     * @return mixed|null|string
     */
    public function get(int $index)
    {


        if ($this->model) {
            if (!isset($this->hydratedRecords[$index])) {
                $this->hydratedRecords[$index] = Hydrator::hydrate($this->records[$index], $this->model);
            }
            return $this->hydratedRecords[$index];
        }
        return $this->model;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->get($this->index);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->records[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->all()[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return Exception
     * @throws Exception
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception("Can't alter records");
    }

    /**
     * @param mixed $offset
     * @throws Exception
     */
    public function offsetUnset($offset)
    {
        throw new Exception("Can't alter records");
    }
}
