<?php

namespace Core\Services;

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class PhinxService
{
    private static $instances = [];

    public static function getManager(): PhinxService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PhinxService();
        }
        return self::$instances[$cls];
    }

    public static function getPhinxApplication(): PhinxApplication
    {
        $cls = PhinxApplication::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PhinxApplication();
        }
        return self::$instances[$cls];
    }

    private function run($cmd) : void
    {
        self::getPhinxApplication()->doRun(new StringInput($cmd), new NullOutput());
    }

    public function migrate($environment)
    {
        $this->run("migrate -e $environment");
    }

    public function seed($environment)
    {
        $this->run("seed:run -e $environment");
    }

    public function rollback($environment, $targetVersion = null)
    {
        $this->run("rollback -e $environment". $targetVersion ? " -t $targetVersion" : '');
    }
}