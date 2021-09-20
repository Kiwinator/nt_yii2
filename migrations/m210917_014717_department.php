<?php

use yii\db\Migration;

/**
 * Class m210917_014717_department
 */
class m210917_014717_department extends Migration
{
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%department}}', [
			'id' => $this->primaryKey()->comment('Код'),
			'name' => $this->string()->notNull()->comment('Название отдела'),
			'deleted' => $this->boolean()->null()->defaultValue(false)->comment('Удален'),
		], $tableOptions);
    }

    public function down() {
        try {
            $this->dropTable('{{%department}}');
        }  catch (\Exception $e) {
            echo "Уже отсутствует";
        }

    }
}
