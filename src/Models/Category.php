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

/**
 * Category
 *
 * Category model
 *
 * @category Application
 * @package  Application/Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Category extends Model
{
    public const TABLE = 'categories';

    public string $name;

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
            'name',
        ];
    }
}
