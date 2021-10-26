<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PartnerLocator;
use app\models\LocCountry;
use app\models\LocState;
use yii\helpers\Html;

class PartnerLocatorSearch extends PartnerLocator
{
    public $name;
    public $country;
    public $state;
    public $status;
    
    public function rules() {
        return [
            [['status','country','state'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = PartnerLocator::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>[
                    'id' => SORT_ASC,
                ],
                'attributes'=>[
                    'id',
                    'name',
                    'logo',
                    'company',
                    'address',
                    'website',
                    'phone',
                    'status'
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(["or",["like", "company", html::encode($this->name)],["like", "address", html::encode($this->name)]]);
        if ($this->country && is_numeric($this->country)) {
        	$query->andFilterWhere(["like", "countries_covered", locCountry::findOne(html::encode($this->country))->short_name]);
        }
        if ($this->state && is_numeric(html::encode($this->state))) {
        	$query->andFilterWhere(["like", "states_covered", LocState::findOne(html::encode($this->state))->short_name]);
        }
        $query->andFilterWhere(["status" => html::encode($this->status)]);

        return $dataProvider;
    }
}