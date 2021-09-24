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
    	$transaction = self::getDb()->beginTransaction();
		try {
	        $this->name = html::encode($this->name);
	        $this->save();
	        $transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
    }
    
    public function updateDepartment() {
        $transaction = self::getDb()->beginTransaction();
		try {
        	$department = self::findOne($this->id);
	        $department->name = html::encode($this->name);
		    $department->deleted = false;
	        $department->save(false);
	        $transaction->commit();
		} catch(\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch(\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
    }

    public static function deleteDepartment($info) {
    	$id = HTML::encode($info['id']);
    	$departmentEmployee = new DepartmentEmployee();
    	$toDelete = $departmentEmployee->toDelete();
    	if (!in_array($id, $toDelete)) {
	        $transaction = self::getDb()->beginTransaction();
			try {
	        	$department = self::findOne($id);
		        $department->deleted = true;
		        $department->save();
		        
		        $departmentEmployee->deleteLink(HTML::encode($info['id']));
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
    
    public static function activeDepartment () {
    	$department = self::find()->where(['deleted'=>false])->all();
    	return ArrayHelper::map($department, 'id', 'id');
    }
    
}