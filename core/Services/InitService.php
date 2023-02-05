<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Services;

use Core\Repositories\InitRepository;

/**
 * InitService
 *
 * Create, migrate and seed the database
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class InitService extends Service
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create
     *
     * Create the database
     *
     * @return void
     */
    public static function create()
    {
        InitRepository::createDatabase();
    }

    /**
     * Migrate
     *
     * Migrate the database
     *
     * @param string $env Environment
     *
     * @return void
     */
    public static function migration($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->appMigrate($env);
    }

    /**
     * Seed
     *
     * Seed the database
     *
     * @param string $env Environment
     *
     * @return void
     */
    public static function seed($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->appSeed($env);
    }

    /**
     * Delete
     *
     * Delete the database
     *
     * @return void
     */
    public function delete()
    {
        $this->getRepository()->deleteDatabase();
    }
}
