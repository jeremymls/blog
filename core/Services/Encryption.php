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

use Core\Middleware\Superglobals;

/**
 * Encryption Service
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Encryption
{
    private static $encrypt_method = "AES-256-CBC";

    /**
     * Key
     *
     * Get the secret key and hash it
     *
     * @return string The hashed secret key
     */
    public static function key()
    {
        return hash("sha256", Superglobals::getInstance()->getSecretKey());
    }

    /**
     * Encrypt
     *
     * Encrypt a string
     *
     * @param string $string The string to encrypt
     *
     * @return string The encrypted string
     */
    public static function encrypt($string)
    {
        $output = false;
        $output = openssl_encrypt($string, self::$encrypt_method, self::key());
        $output = base64_encode($output);
        return $output;
    }

    /**
     * Decrypt
     *
     * Decrypt a string
     *
     * @param string $string The string to decrypt
     *
     * @return string The decrypted string
     */
    public static function decrypt($string)
    {
        $output = false;
        $output = openssl_decrypt(
            base64_decode($string),
            self::$encrypt_method,
            self::key()
        );
        return $output;
    }
}
