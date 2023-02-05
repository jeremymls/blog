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
 * MailContact Model
 *
 * @category Core
 * @package  Core\Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class MailContactModel extends Model
{
    public $name;
    public $email;
    public $phone;
    public $message;

    /**
     * Get Fillable
     *
     * Return the fillable fields
     *
     * @return array
     */
    public function getFillable(): array
    {
        return ['name', 'email', 'message'];
    }
}
