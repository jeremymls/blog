<?php

namespace Core\Models;

class Model
{
    static string $id;
    static string $created_at;
    // static string $updated_at;
    // static string $deleted_at;

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
}
