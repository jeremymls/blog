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
 * Config model
 *
 * @category Core
 * @package  Core\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Config extends Model
{
    public const TABLE = 'configs';

    public string $name;
    public ?string $value;
    public ?string $description;
    public ?string $type;
    public ?string $default_value;

    /**
     * Get Fillable
     *
     * Return the fillable fields
     *
     * @return array
     */
    public function getFillable(): array
    {
        return ['name'];
    }
}
