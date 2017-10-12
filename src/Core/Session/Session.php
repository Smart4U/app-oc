<?php


namespace App\Core\Session;

/**
 * Class Session
 * @package App\Core\Session
 */
class Session implements SessionInterface
{


    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    public function get(string $key, $default = null)
    {
        $this->initSession();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->initSession();
        $_SESSION[$key] = $value;
    }


    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->initSession();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }


    /**
     * Don't rewrite session_start if already started
     */
    private function initSession() :void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}