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
 * Token model
 *
 * @category Core
 * @package  Core\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Token extends Model
{
    public const TABLE = 'tokens';

    public $user_id;
    public string $token;
    public string $expiration_date;

    /**
     * GetLinks
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
        return ['user_id' => 'UserRepository'];
    }
}
