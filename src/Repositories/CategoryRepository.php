<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Repositories;

use Application\Models\Category;
use Core\Repositories\Repository;

/**
 * CategoryRepository
 *
 * Category Repository
 *
 * @category Application
 * @package  Application\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class CategoryRepository extends Repository
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }
}
