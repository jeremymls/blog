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
 * Post
 *
 * Post model
 *
 * @category Application
 * @package  Application/Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Post extends Model
{
    public const TABLE = 'posts';

    public $category;
    public string $title;
    public string $content;
    public string $url;
    public string $chapo;
    public string $picture;

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
            'title',
            'content',
        ];
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
        return [
            'category' => 'CategoryRepository',
        ];
    }
}
