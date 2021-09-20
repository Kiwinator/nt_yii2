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
    
    public function rules() {
        return [
            [['id'],'integer'],
            [['name', 'department'], 'string'],
            [['deleted'], 'boolean'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Employee::find()
        	->select(['employee.id', 'employee.name', 'group_concat(department.name) as department'])
        	->joinWith(['departmentEmployees','departmentEmployees.departments'])
        	->where(['employee.deleted' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        $query->andFilterWhere(["like", "employee.name", html::encode($this->name)]);
        $query->andFilterWhere(["like", "department.name", html::encode($this->department)]);
        $query->groupBy(['employee.id', 'employee.name']);
        return $dataProvider;
    }
}