<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Models;

/**
 * Model base class
 *
 * Manage the models
 *
 * @category Core
 * @package  Core\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Model
{
    public static string $id;
    public static string $created_at;

    /**
     * Set Id
     *
     * Set the id of the model
     *
     * @param mixed $id The id of the model
     *
     * @return void
     */
    public function setId($id)
    {
        self::$id = $id;
    }

    /**
     * Set Created At
     *
     * Set the creation date of the model
     *
     * @param mixed $date The creation date of the model
     *
     * @return void
     */
    public function setCreatedAt($date)
    {
        self::$created_at = $date;
    }

    /**
     * Get French Creation Date
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
     * With
     *
     * Get the linked model
     *
     * @param string $field      The field of the model
     * @param string $repository The repository of the model
     *
     * @return void
     */
    public function with($field, $repository)
    {
        $id = $this->$field;
        $repository = new $repository();
        $this->$field = $repository->findOne($id);
    }

    /**
     * Get Fillable
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
     * Get Links
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
