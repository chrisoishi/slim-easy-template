<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AuthMigration extends AbstractMigration
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
    public function change(): void
    {
        // create the table
        $table = $this->table('users');
        $table
            ->addColumn('email', 'string')
            ->addColumn('name', 'string')
            ->addColumn('password', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addIndex(['email'], ['unique' => true])
            ->create();

        $table2 = $this->table('auth_tokens',["id"=>false]);
        $table2
            ->addColumn('user_id', 'integer', ['null' => true])
            ->addColumn('token', 'string')
            ->addColumn('refresh_token', 'string')
            ->addColumn('description', 'text')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->addIndex(['token',"refresh_token"], ['unique' => true])
            ->create();
    }
}
