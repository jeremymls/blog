<?php

namespace Core\Services;

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * PhinxService
 * 
 * Run Phinx commands
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
     * getPhinxApplication
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
     * run
     * 
     * Run Phinx command
     * 
     * @param string $cmd
     */
    private function run($cmd) : void
    {
        self::getPhinxApplication()->doRun(new StringInput($cmd), new NullOutput());
    }

    /**
     * AppMigrate
     * 
     * Run Phinx migrate command
     * 
     * @param string $environment
     */
    public function AppMigrate($environment)
    {
        $this->run("migrate -e $environment");
    }

    /**
     * AppSeed
     * 
     * Run Phinx seed command
     * 
     * @param string $environment
     */
    public function AppSeed($environment)
    {
        $this->run("seed:run -e $environment");
    }
}
