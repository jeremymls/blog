<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class Comments extends AbstractMigration
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
        $this->table('comments')
            ->addColumn('post', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('author', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('comment', 'text', ['null' => false])
            ->addColumn('created_at', 'datetime', ['default' => Literal::from('CURRENT_TIMESTAMP')])
            ->addColumn('moderate', 'boolean', ['default' => 0, 'null' => false])
            ->addColumn('deleted', 'boolean', ['default' => 0, 'null' => false])
            ->addForeignKey('post', 'posts', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
            ->addForeignKey('author', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->create();
    }
}