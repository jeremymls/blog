<?php

namespace Core\Services;
use Core\Middleware\Superglobals;

/**
 * Encryption Service
 */
class Encryption
{
    private static $encrypt_method = "AES-256-CBC";

    /**
     * key
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
     * encrypt
     * 
     * Encrypt a string
     *
     * @param  string $string The string to encrypt
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
     * decrypt
     * 
     * Decrypt a string
     *
     * @param  string $string The string to decrypt
     * @return string The decrypted string
     */
    public static function decrypt($string)
    {
        $output = false;
        $output = openssl_decrypt(base64_decode($string), self::$encrypt_method, self::key());
        return $output;
    }
}