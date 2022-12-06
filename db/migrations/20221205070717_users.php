<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class Users extends AbstractMigration
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
        $this->table('users')
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 1000, 'null' => false])
            ->addColumn('first', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('last', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('username', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('picture', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('role', 'string', ['limit' => 10, 'default' => 'user', 'null' => false])
            ->addColumn('validated_email', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('created_at', 'datetime', ['default' => Literal::from('CURRENT_TIMESTAMP')])
            ->create();
    }
}
