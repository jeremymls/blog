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
 * FlashService
 *
 * Send flash messages in queue to be displayed in the view
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class FlashService
{
    private static $instances = [];
    private $sessionKey = 'flash';
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
     * Get the instance of the class
     *
     * @return FlashService
     */
    public static function getInstance(): FlashService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new FlashService();
        }
        return self::$instances[$cls];
    }

    /**
     * Success
     *
     * Send a success flash message
     *
     * @param string $title
     * @param string $message
     *
     * @return void
     */
    public function success(string $title, string $message): void
    {
        $this->send('success', $title, $message);
    }

    /**
     * Danger
     *
     * Send a danger flash message
     *
     * @param string $title
     * @param string $message
     *
     * @return void
     */
    public function danger(string $title, string $message): void
    {
        $this->send('danger', $title, $message);
    }

    /**
     * Info
     *
     * Send an info flash message
     *
     * @param string $title
     * @param string $message
     *
     * @return void
     */
    public function info(string $title, string $message): void
    {
        $this->send('info', $title, $message);
    }

    /**
     * Warning
     *
     * Send a warning flash message
     *
     * @param string $title
     * @param string $message
     *
     * @return void
     */
    public function warning(string $title, string $message): void
    {
        $this->send('warning', $title, $message);
    }

    /**
     * Template
     *
     * Send a flash message from a template
     *
     * @param string $template The template name
     *
     * @return void
     */
    public function template(string $template): void
    {
        $this->send($template);
    }

    /**
     * Send
     *
     * Send a flash message to the queue
     *
     * @param string $type    The type of flash message
     * @param string $title   The title
     * @param string $message The message
     *
     * @return void
     */
    private function send(
        string $type,
        string $title = "",
        string $message = ""
    ): void {
        $flash = $this->session->get($this->sessionKey, []);
        $flash[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * Get
     *
     * Get the flash messages from the queue and delete them
     *
     * @return array
     */
    public function get()
    {
        $flash = $this->session->get($this->sessionKey, []);
        $this->session->delete($this->sessionKey);
        return $flash;
    }
}
