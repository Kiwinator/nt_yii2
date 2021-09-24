<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee;
use yii\helpers\Html;

class EmployeeSearch extends Employee
{
    public $id;
    public $name;
    public $department;
    public $deleted;
    
    public function rules() {
        return [
            [['id'],'integer'],
            [['name', 'department'], 'string'],
            [['deleted'], 'integer'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Employee::find()
        	->select(['employee.id', 'employee.name', 'group_concat(department.name) as department', 'employee.deleted'])
        	->joinWith(['departmentEmployees','departmentEmployees.departments']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
		        'pageSize' => 20,
		    ],
            'sort' => [
                'defaultOrder'=>[
                    'id' => SORT_DESC,
                ],
                'attributes'=>[
                    'id',
                    'name',
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(["like", "employee.id", html::encode($this->id)]);
        $query->andFilterWhere(["ilike", "employee.name", html::encode($this->name)]);
        $query->andFilterWhere(["ilike", "department.name", html::encode($this->department)]);
        $query->andFilterWhere(["employee.deleted" => html::encode($this->deleted)]);
        $query->groupBy(['employee.id', 'employee.name', 'employee.deleted']);
        return $dataProvider;
    }
}