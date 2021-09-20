<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DepartmentEmployee;
use yii\helpers\Html;

class DepartmentEmployeeSearch extends DepartmentEmployee
{
    public $id;
    public $employee_name;
    public $department_name;
    
    public function rules() {
        return [
            [['id'],'integer'],
            [['employee_name', 'department_name'], 'string'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = DepartmentEmployee::find()
        	->select(['department_employee.id', 'employee.name as employee_name','department.name as department_name'])
        	->joinWith(['departments','employees']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>[
                    'employee_name' => SORT_DESC,
                ],
                'attributes'=>[
                    'id',
                    'employee_name',
                    'department_name',
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(["like", "department_employee.id", html::encode($this->id)]);
        $query->andFilterWhere(["like", "employee.name", html::encode($this->employee_name)]);
        $query->andFilterWhere(["like", "department.name", html::encode($this->department_name)]);
        
        return $dataProvider;
    }
}