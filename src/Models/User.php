<?php

namespace Application\Models;

use Core\Models\Model;
use Core\Repositories\TokenRepository;
use DateTime;

/**
 * User
 * 
 * User model
 */
class User extends Model
{
    const TABLE = 'users';

    public string $username;
    public string $password;
    public string $email;
    public string $first;
    public string $last;
    public string $role;
    public string $validated_email;
    public string $picture;
    private string $token;

    public function getFillable(): array
    {
        return [
            'password',
            'email',
            'first',
            'last',
            'role',
        ];
    }

    /**
     * setPassword
     * 
     * Set the password with the hash password
     * 
     * @param  string $password
     */
    public function setPassword($password)
    {
        $this->password = self::hashPassword($password);
    }

    /**
     * comparePassword
     * 
     * Compare the password with the password in the database
     * 
     * @param  string $passwordBDD
     * @param  string $passwordPOST
     * @return bool
     */
    public function comparePassword($passwordBDD, $passwordPOST)
    {
        return self::hashPassword($passwordPOST) === $passwordBDD;
    }

    /**
     * hashPassword
     * 
     * Hash the password
     * 
     * @param  string $password
     * @return string
     */
    private static function hashPassword($password)
    {
        return hash("sha512", hash("ripemd256", $password));
    }

    /**
     * withExpirationToken
     * 
     * Check if the token exist and if it's expired and set it
     */
    public function withExpirationToken()
    {
        $tokenRepository = new TokenRepository();
        $token = $tokenRepository->findAll("WHERE user_id = ?", [self::$id]);
        if (count($token) > 0) {
            if (new DateTime($token[0]->expiration_date)>new DateTime()) {
                $this->token = $token[0]->expiration_date;
            } else {
                $this->token = 'expired';
            }
        } else {
            $this->token = 'not exist';
        }
    }

    /**
     * getToken
     * 
     * Return the token
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}
