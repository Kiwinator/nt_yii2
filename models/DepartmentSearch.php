<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;
use yii\helpers\Html;

class DepartmentSearch extends Department
{
    public $id;
    public $name;
    public $deleted;
    
    public function rules() {
        return [
        	[['id'],'integer'],
            [['name'], 'safe'],
            [['deleted'], 'integer'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Department::find();

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

        $query->andFilterWhere(["like", "id", html::encode($this->id)]);
        $query->andFilterWhere(["ilike", "name", html::encode($this->name)]);
        $query->andFilterWhere(["deleted" => html::encode($this->deleted)]);

        return $dataProvider;
    }
}