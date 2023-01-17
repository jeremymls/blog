<?php

namespace Core\Models;

/**
 * Model base class
 * 
 * Manage the models
 */
class Model
{
    static string $id;
    static string $created_at;
    private string $csrf_token;
    // static string $updated_at;
    // static string $deleted_at;

    /**
     * setId
     * 
     * Set the id of the model
     *
     * @param  mixed $id The id of the model
     */
    public function setId($id)
    {
        self::$id = $id;
    }

    /**
     * setCreatedAt
     * 
     * Set the creation date of the model
     *
     * @param  mixed $date The creation date of the model
     */
    public function setCreatedAt($date)
    {
        self::$created_at = $date;
    }

    /**
     * getFrenchCreationDate
     * 
     * Return the creation date of the model in french format
     *
     * @return string
     */
    public static function getFrenchCreationDate()
    {
        return date('d/m/Y à H:i', strtotime(static::$created_at));
    }

    /**
     * with
     * 
     * Get the linked model
     *
     * @param  string $field The field of the model
     * @param  string $repository The repository of the model
     */
    public function with($field, $repository) 
    {
        $id = $this->$field;
        $repository = new $repository();
        $this->$field = $repository->findOne($id);
    }

    /**
     * getFillable
     * 
     * Return the fillable fields
     *
     * @return array
     */
    public function getFillable()
    {
        return [];
    }

    /**
     * getLinks
     * 
     * Return the links between the model and other models:
     * ['field' => 'Repository']
     * 
     * Example: ['category' => 'CategoryRepository']
     *
     * @return array
     */
    public function getLinks()
    {
        return [];
    }
}
