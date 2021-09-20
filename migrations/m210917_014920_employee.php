<?php

use yii\db\Migration;

/**
 * Class m210917_014920_employee
 */
class m210917_014920_employee extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%employee}}', [
			'id' => $this->primaryKey()->comment('Код'),
			'name' => $this->string()->notNull()->comment('ФИО сотрудика'),
			'deleted' => $this->boolean()->null()->defaultValue(false)->comment('Удален'),
		], $tableOptions);
    }

    public function down() {
        try {
            $this->dropTable('{{%employee}}');
        }  catch (\Exception $e) {
            echo "Уже отсутствует";
        }

    }
}
