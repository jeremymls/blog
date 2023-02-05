<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Models;

use Core\Models\Model;
use Core\Repositories\TokenRepository;
use DateTime;

/**
 * User
 *
 * User model
 *
 * @category Application
 * @package  Application/Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class User extends Model
{
    public const TABLE = 'users';

    public string $username;
    public string $password;
    public string $email;
    public string $first;
    public string $last;
    public string $role;
    public string $validated_email;
    public string $picture;
    private string $token;

    /**
     * Get Fillable
     *
     * Return the fillable fields
     *
     * @return array
     */
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
     * Set Password
     *
     * Set the password with the hash password
     *
     * @param string $password Password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = self::hashPassword($password);
    }

    /**
     * Compare Password
     *
     * Compare the password with the password in the database
     *
     * @param string $passwordBDD  Password in the database
     * @param string $passwordPOST Password in the form
     *
     * @return bool
     */
    public function comparePassword($passwordBDD, $passwordPOST)
    {
        return self::hashPassword($passwordPOST) === $passwordBDD;
    }

    /**
     * Hash Password
     *
     * Hash the password
     *
     * @param string $password Password
     *
     * @return string
     */
    private static function hashPassword($password)
    {
        return hash("sha512", hash("ripemd256", $password));
    }

    /**
     * With Expiration Token
     *
     * Check if the token exist and if it's expired and set it
     *
     * @return void
     */
    public function withExpirationToken()
    {
        $tokenRepository = new TokenRepository();
        $token = $tokenRepository->findAll("WHERE user_id = ?", [self::$id]);
        if (count($token) > 0) {
            if (new DateTime($token[0]->expiration_date) > new DateTime()) {
                $this->token = $token[0]->expiration_date;
            } else {
                $this->token = 'expired';
            }
        } else {
            $this->token = 'not exist';
        }
    }

    /**
     * Get Token
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
