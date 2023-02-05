<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware\Session
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware\Session;

/**
 * PHPSession
 *
 * Manage the PHP session
 *
 * @category Core
 * @package  Core\Middleware\Session
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class PHPSession
{
    private static $instances = [];

    /**
     * __construct
     *
     * Launch the session if it is not started yet
     */
    public function __construct()
    {
        $this->ensureStarted();
    }

    /**
     * Singleton
     *
     * @return PHPSession
     */
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PHPSession();
        }
        return self::$instances[$cls];
    }

    /**
     * Ensure Started
     *
     * Start the session if it is not started yet and if we are not in CLI mode
     *
     * @return void
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
            session_start();
        }
    }

    /**
     * Get
     *
     * Get a value from the session
     *
     * @param string $key     The key of the value to get
     * @param mixed  $default The default value to return if the key is not found
     *
     * @return mixed The value or the default value
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Set
     *
     * Set a value in the session
     *
     * @param string $key   The key of the value to set
     * @param mixed  $value The value to set
     *
     * @return mixed The value or the default value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Delete
     *
     * Delete a value from the session
     *
     * @param string $key The key of the value to delete
     *
     * @return void
     */
    public function delete(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Redirect Last Url
     *
     * Redirect the user to the last page they were on
     *
     * @param string|null $anchor The anchor to add to the url
     *
     * @return void
     */
    public function redirectLastUrl($anchor = null)
    {
        header('Location: ' . $this->getLastUrl($anchor));
    }

    /**
     * Get Last Url
     *
     * Get the last page the user was before starting an action
     *
     * @param string|null $anchor The anchor to add to the url
     *
     * @return string
     */
    public function getLastUrl($anchor = null)
    {
        $last_url = $this->get('last_url');
        if ($last_url) {
            $this->delete('last_url');
            return $last_url . ($anchor ? ('#' . $anchor) : '');
        }
        return '/';
    }
}
