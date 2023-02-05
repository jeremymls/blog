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

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * PhinxService
 *
 * Run Phinx commands
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class PhinxService
{
    private static $instances = [];

    /**
     * Singleton
     *
     * @return PhinxService
     */
    public static function getManager(): PhinxService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PhinxService();
        }
        return self::$instances[$cls];
    }

    /**
     * Get Phinx Application
     *
     * Get Phinx Application
     *
     * @return PhinxApplication
     */
    public static function getPhinxApplication(): PhinxApplication
    {
        $cls = PhinxApplication::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PhinxApplication();
        }
        return self::$instances[$cls];
    }

    /**
     * Run
     *
     * Run Phinx command
     *
     * @param string $cmd The command to run
     *
     * @return void
     */
    private function run($cmd): void
    {
        self::getPhinxApplication()->doRun(new StringInput($cmd), new NullOutput());
    }

    /**
     * App Migrate
     *
     * Run Phinx migrate command
     *
     * @param string $environment The environment to run the command
     *
     * @return void
     */
    public function appMigrate($environment)
    {
        $this->run("migrate -e $environment");
    }

    /**
     * App Seed
     *
     * Run Phinx seed command
     *
     * @param string $environment The environment to run the command
     *
     * @return void
     */
    public function appSeed($environment)
    {
        $this->run("seed:run -e $environment");
    }
}
