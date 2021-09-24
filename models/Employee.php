<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use app\models\Department;
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
        $transaction = self::getDb()->beginTransaction();
        $Department = new Department();
        $DepartmentList = $Department->activeDepartment();
        $DepartmentCheck = array_intersect($this->department,$DepartmentList);
        if (!empty($DepartmentCheck)) {
			try {
	        	$departmentEmployee = new DepartmentEmployee();
			    $this->name = html::encode($this->name);
	        	$this->save(false);
			    $departmentEmployee->establishLink($this->id, $DepartmentCheck);
			    $transaction->commit();
			} catch(\Exception $e) {
			    $transaction->rollBack();
			    throw $e;
			} catch(\Throwable $e) {
			    $transaction->rollBack();
			    throw $e;
			}
        }
    }
    
    public function updateEmployee() {
        $transaction = self::getDb()->beginTransaction();
        $Department = new Department();
        $DepartmentList = $Department->activeDepartment();
        $DepartmentCheck = array_intersect($this->department,$DepartmentList);
        if (!empty($DepartmentCheck)) {
	        try {
	        	$employee = self::findOne($this->id);
		        $employee->name = html::encode($this->name);
		        $employee->deleted = false;
		        $employee->save(false);
	        	$departmentEmployee = new DepartmentEmployee();
		        $departmentEmployee->establishLink($employee->id, $DepartmentCheck);
		        $transaction->commit();
	        } catch(\Exception $e) {
			    $transaction->rollBack();
			    throw $e;
			} catch(\Throwable $e) {
			    $transaction->rollBack();
			    throw $e;
			}
        }
        return $DepartmentCheck;
    }

    public static function deleteEmployee($info) {
        $transaction = self::getDb()->beginTransaction();
        try {
    		$employee = self::findOne(HTML::encode($info['id']));
	        $employee->deleted = true;
	        $employee->save(false);
        	$departmentEmployee = new DepartmentEmployee();
	        $departmentEmployee->deleteLink(null,HTML::encode($info['id']));
	        $transaction->commit();
        } catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
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
