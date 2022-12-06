<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class Posts extends AbstractMigration
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
        $this->table('posts')
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('category', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('chapo', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('content', 'text', ['null' => false])
            ->addColumn('picture', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('url', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created_at', 'datetime', ['default' => Literal::from('CURRENT_TIMESTAMP')])
            ->addForeignKey('category', 'categories', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->create();
    }
}
