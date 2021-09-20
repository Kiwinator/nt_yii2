<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use app\models\DepartmentEmployee;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "department".
 *
 * @property int $id Код
 * @property string $name Название отдела
 * @property int|null $deleted Удален
 *
 * @property DepartmentEmployee[] $departmentEmployees
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Наименование',
            'deleted' => 'Удален',
        ];
    }

    /**
     * Gets query for [[DepartmentEmployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentEmployees()
    {
        return $this->hasMany(DepartmentEmployee::className(), ['department_id' => 'id']);
    }
    
    public function createDepartment() {
        $this->name = html::encode($this->name);
        $this->save();
    }
    
    public function updateDepartment() {
        $department = self::findOne($this->id);
        $department->name = html::encode($this->name);
        $department->save(false);
    }

    public function deleteDepartment($info) {
        $department = self::findOne(HTML::encode($info['id']));
        $department->deleted = true;
        $department->save(false);
        DepartmentEmployee::deleteLink(HTML::encode($info['id']));
    }
    
    public function toDelete() {
    	$subQ = DepartmentEmployee::find()->select(['department_id'])->groupBy(['employee_id'])->having(['count(department_id)' => 1])->all();
    	return ArrayHelper::map($subQ, 'department_id', 'department_id');
    }
    
}
