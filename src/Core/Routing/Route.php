<?php

namespace App\Core\Routing;

/**
 * Define a route
 *
 * Class Route
 * @package App\Core\Routing
 */
class Route
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var callable|string
     */
    private $handler;

    /**
     * @var array
     */
    private $params;

    /**
     * Route constructor.
     * @param string $name
     * @param $handler
     * @param array $params
     */
    public function __construct(string $name, $handler, array $params = [])
    {
        $this->setRouteName($name);
        $this->setRouteHandler($handler);
        $this->setRouteParams($params);
    }

    /**
     * @param string $name
     */
    public function setRouteName(?string $name = null)
    {
        $this->name = $name;
    }

    /**
     * @param $handler
     */
    public function setRouteHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param array $params
     */
    public function setRouteParams(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @return null|string
     */
    public function getRouteName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|callable
     */
    public function getRouteHandler()
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->params;
    }
}
