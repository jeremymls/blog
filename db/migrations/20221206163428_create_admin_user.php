<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAdminUser extends AbstractMigration
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
        $rows = [
            [
                'id' => 1,
                'email'  => 'admin@jm-projet.fr',
                'username' => 'admin',
                'password' => "2cccdc2687174d99b72adf3e151cdc42d77fd16d4de993972261d0cadf002406efeca3991a8bcb2d265d44c4e460a60c9eb357008733d9ba10ba52bce99e45ef",
                'first' => "Owner_first",
                'last' => "Owner_last",
                'role' => "admin",
                'validated_email' => 1,
            ]
        ];
        $this->table('users')->insert($rows)->update();
    }

    public function down()
    {
        $this->table('users')->drop()->save();
    }
}
