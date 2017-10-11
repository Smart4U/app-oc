<?php

namespace App\Core\Notify;

use App\Core\Session\SessionInterface;

/**
 * Class Flash
 * @package App\Core\Notify
 */
class Flash
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var null
     */
    private $messages = null;
    /**
     * @var string
     */
    private $key = 'flash';

    /**
     * Flash constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $message
     */
    public function success(string $message)
    {
        $flash = $this->session->get($this->key, []);
        $flash['success'] = $message;
        $this->session->set($this->key, $flash);
    }

    /**
     * @param string $message
     */
    public function error(string $message)
    {
        $flash = $this->session->get($this->key, []);
        $flash['error'] = $message;
        $this->session->set($this->key, $flash);
    }

    /**
     * @param string $message
     */
    public function warning(string $message)
    {
        $flash = $this->session->get($this->key, []);
        $flash['warning'] = $message;
        $this->session->set($this->key, $flash);
    }

    /**
     * @param string $message
     */
    public function info(string $message)
    {
        $flash = $this->session->get($this->key, []);
        $flash['info'] = $message;
        $this->session->set($this->key, $flash);
    }

    /**
     * @param string $type
     * @return null|string
     */
    public function get(string $type): ?string
    {
        if (is_null($this->messages)) {
            $this->messages = $this->session->get($this->key, []);
            $this->session->delete($this->key);
        }
        if (array_key_exists($type, $this->messages)) {
            return $this->messages[$type];
        }
        return null;
    }
}
