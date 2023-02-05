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
 * Comment
 *
 * Comment Model
 *
 * @category Application
 * @package  Application/Models
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Comment extends Model
{
    public const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
    public string $deleted;

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
            'post' => 'PostRepository',
            'author' => 'UserRepository',
        ];
    }

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
            'post',
            'author',
            'comment',
        ];
    }
}
