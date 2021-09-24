<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "department_employee".
 *
 * @property int $id Код
 * @property string $name Название отдела
 * @property int|null $deleted Удален
 *
 * @property DepartmentEmployee[] $departmentEmployees
 */
class DepartmentEmployee extends \yii\db\ActiveRecord
{
	public $department_name;
	public $employee_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id','department_id'], 'required'],
            [['department_name', 'employee_name'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'department_id' => 'Код отдела',
            'employee_id' => 'Код сотрудника',
            'department_name' => 'Наименование отдела',
            'employee_name' => 'ФИО сотрудника',
        ];
    }
    
    public static function deleteLink($departmentId = null, $employeeId = null) {
    	$transaction = self::getDb()->beginTransaction();
		try {
	    	if ($employeeId) {
	    		self::deleteAll(['employee_id'=>$employeeId]);
	    	}
	    	if ($departmentId) {
	    		self::deleteAll(['department_id'=>$departmentId]);
	    	}
    		$transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
    }
    
    public static function establishLink($employeeId, $departments) {
    	$transaction = self::getDb()->beginTransaction();
    	try {
	    	$establishedLink = self::find()->where(['employee_id' => $employeeId])->all();
	    	$linkArray = ArrayHelper::map($establishedLink, 'department_id', 'department_id');
	    	$linkArrayAdd = array_diff($departments,$linkArray);
	    	$linkArrayRemove = array_diff($linkArray,$departments);
	    	if ($linkArrayRemove) {
	    		self::deleteAll(['AND',['employee_id'=>$employeeId],['IN', 'department_id', $linkArrayRemove]]);
	    	};
	    	foreach ($linkArrayAdd as $department) {
	    		if (!in_array($department, $linkArray)) {
		    		$model = new self();
		    		$model->employee_id = $employeeId;
		    		$model->department_id = $department;
		    		$model->save();
	    		}
	    	}
	    	$transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
    }
    
    public static function toDelete() {
    	$subQ = self::find()->select(['department_id'])->groupBy(['employee_id'])->having(['count(department_id)' => 1])->all();
    	return ArrayHelper::map($subQ, 'department_id', 'department_id');
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['id' => 'department_id']);
    }
    
    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id' => 'employee_id']);
    }
}
