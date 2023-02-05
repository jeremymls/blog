<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Services;

use Application\Models\Category;
use Core\Services\EntityService;

/**
 * CategoryService
 *
 * Category Service
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class CategoryService extends EntityService
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
