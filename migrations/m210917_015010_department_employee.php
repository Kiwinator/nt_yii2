<?php

use yii\db\Migration;

/**
 * Class m210917_015010_department_employee
 */
class m210917_015010_department_employee extends Migration
{
   public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%department_employee}}', [
			'id' => $this->primaryKey()->comment('Код'),
			'employee_id' => $this->integer()->notNull()->comment('Код сотрудника'),
			'department_id' => $this->integer()->notNull()->comment('Код отдела'),
		], $tableOptions);
		
		$this->addForeignKey(
            'department_employee_fk_department_id',
            'department_employee',
            'department_id',
            'department',
            'id',
            'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'department_employee_fk_employee_id',
            'department_employee',
            'employee_id',
            'employee',
            'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function down() {
        try {
            $this->dropTable('{{%department_employee}}');
        }  catch (\Exception $e) {
            echo "Уже отсутствует";
        }

    }
}
