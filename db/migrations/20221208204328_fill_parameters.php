<?php
declare(strict_types=1);
include_once 'src/config/default.php';

use Phinx\Migration\AbstractMigration;

final class FillParameters extends AbstractMigration
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
    public function up()
    {
        foreach (CONFIGS as $key => $value) {
            $this->table('configs')->insert([
                'name' => $key,
                'value' => $value[0],
                'description' => $value[1],
                'type' => isset($value[2])? $value[2] : 'text',
                'default_value' => $value[0]
            ])->save();
        }
    }

    public function down()
    {
        $this->table('configs')->drop()->save();
    }
}
