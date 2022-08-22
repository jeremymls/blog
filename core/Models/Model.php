<?php

namespace Core\Models;

use Core\Repositories\TokenRepository;
use DateTime;

class Model
{
    static string $id;
    static string $created_at;
    // static string $updated_at;
    // static string $deleted_at;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this;
    }

    /**
     * Set the value of id
     */ 
    public function setId($id)
    {
        self::$id = $id;
    }

    /**
     * Set the value of created_at
     */ 
    public function setCreatedAt($date)
    {
        self::$created_at = $date;
    }

    public static function getFrenchCreationDate()
    {
        return date('d/m/Y à H:i', strtotime(static::$created_at));
    }

    public function with($field, $repository) 
    {
        $id = $this->$field;
        $repository = new $repository();
        $this->$field = $repository->findOne($id);
    }

    public function withExpirationToken()
    {
        $tokenRepository = new TokenRepository();
        $token = $tokenRepository->findAll("WHERE user_id = ?", [self::$id]);
        if (count($token) > 0) {
            if (new DateTime($token[0]->expiration_date)>new DateTime()){
                $this->token = $token[0]->expiration_date;
            } else {
                $this->token = 'expired';
            }
        } else {
            var_dump('pas de token');
        }
    }
}