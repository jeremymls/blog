<?php

namespace Core\Services;

use Core\Middleware\Session\PHPSession;

/**
 * FlashService
 * 
 * Send flash messages in queue to be displayed in the view
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
     * success
     * 
     * Send a success flash message
     *
     * @param  string $title
     * @param  string $message
     */
    public function success(string $title, string $message): void
    {
        $this->send('success', $title, $message);
    }

    /**
     * danger
     * 
     * Send a danger flash message
     * 
     * @param string $title
     * @param string $message
     */
    public function danger(string $title, string $message): void
    {
        $this->send('danger', $title, $message);
    }

    /**
     * info
     * 
     * Send an info flash message
     * 
     * @param string $title
     * @param string $message
     */
    public function info(string $title, string $message): void
    {
        $this->send('info', $title, $message);
    }

    /**
     * warning
     * 
     * Send a warning flash message
     * 
     * @param string $title
     * @param string $message
     */
    public function warning(string $title, string $message): void
    {
        $this->send('warning', $title, $message);
    }

    /**
     * template
     * 
     * Send a flash message from a template
     * 
     * @param string $template
     */
    public function template(string $template): void
    {
        $this->send($template);
    }

    /**
     * send
     * 
     * Send a flash message to the queue
     * 
     * @param string $type
     * @param string $title
     * @param string $message
     */
    private function send(string $type, string $title = "", string $message = ""): void
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
        $this->session->set($this->sessionKey, $flash);
    }

    /**
     * get
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
