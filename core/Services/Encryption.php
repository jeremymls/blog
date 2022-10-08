<?php

namespace Core\Services;

class Encryption
{
    private static $encrypt_method = "AES-256-CBC";
    private static $secret_key = 'jmP4s5w0r6d';
    private static $secret_iv = 'P4s5w0r6d';
    public static function key()
    {
        return hash("sha256", self::$secret_key);
    }
    public static function iv()
    {
        return substr(hash("sha256", self::$secret_iv), 0, 16);
    }

    public static function encrypt($string)
    {
        $output = false;
        $output = openssl_encrypt($string, self::$encrypt_method, self::key(), 0, self::iv());
        $output = base64_encode($output);
        return $output;
    }

    public static function decrypt($string)
    {
        $output = false;
        $output = openssl_decrypt(base64_decode($string), self::$encrypt_method, self::key(), 0, self::iv());
        return $output;
    }
}