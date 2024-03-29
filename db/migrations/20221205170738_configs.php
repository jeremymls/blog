<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class Configs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('configs')
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('type', 'string', ['limit' => 50, 'default' => 'text', 'null' => false])
            ->addColumn('default_value', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created_at', 'datetime', ['default' => Literal::from('CURRENT_TIMESTAMP')])
            ->create();
    }
}
