<?php

namespace Application\Models;

use Core\Models\Model;
use Core\Repositories\TokenRepository;
use DateTime;

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

    public function setPassword($password)
    {
        $this->password = self::hashPassword($password);
    }

    public function comparePassword($passwordBDD, $passwordPOST)
    {
        return self::hashPassword($passwordPOST) === $passwordBDD;
    }

    private static function hashPassword($password)
    {
        return hash("sha512", hash("ripemd256", $password));
    }

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
}
