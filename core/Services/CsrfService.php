<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Services;

use Core\Middleware\Session\PHPSession;

/**
 * CsrfService
 *
 * Manage the CSRF token
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class CsrfService
{
    private static $instances = [];
    protected $session;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->session = PHPSession::getInstance();
    }

    /**
     * Singleton
     *
     * @return CsrfService
     */
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new CsrfService();
        }
        return self::$instances[$cls];
    }

    /**
     * Get Token
     *
     * Get the token
     *
     * @return string Token
     */
    private function getToken()
    {
        return $this->session->get("csrf_token");
    }

    /**
     * Generate Token
     *
     * Generate a new token
     *
     * @return string Token
     */
    public function generateToken()
    {
        if (!$this->getToken()) {
            $csrf_token = bin2hex(random_bytes(32));
            $this->session->set("csrf_token", $csrf_token);
        }
        return $this->getToken();
    }

    /**
     * Check Token
     *
     * Check if the token is valid
     *
     * @param string $token Token
     *
     * @return bool
     */
    public function checkToken($token)
    {
        if ($token === $this->getToken()) {
            return true;
        }
        return false;
    }
}
