<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use app\models\DepartmentEmployee;

/**
 * This is the model class for table "employee".
 *
 * @property int $id Код
 * @property string $name ФИО сотрудика
 * @property int|null $deleted Удален
 *
 * @property DepartmentEmployee[] $departmentEmployees
 */
class Employee extends \yii\db\ActiveRecord
{
	public $department;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'department'], 'required'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'name' => 'ФИО',
            'department' => 'Отдел',
            'deleted' => 'Удален',
        ];
    }
    
    public function createEmployee() {
        //$employee = new self();
        $this->name = html::encode($this->name);
        $this->save();
        DepartmentEmployee::establishLink($this->id, $this->department);
    }
    
    public function updateEmployee() {
        $employee = self::findOne($this->id);
        $employee->name = html::encode($this->name);
        $employee->save(false);
        DepartmentEmployee::establishLink($employee->id, $this->department);
    }

    public function deleteEmployee($info) {
        $employee = self::findOne(HTML::encode($info['id']));
        $employee->deleted = true;
        $employee->save(false);
        DepartmentEmployee::deleteLink(null,HTML::encode($info['id']));
    }

    /**
     * Gets query for [[DepartmentEmployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentEmployees()
    {
        return $this->hasMany(DepartmentEmployee::className(), ['employee_id' => 'id']);
    }
}
