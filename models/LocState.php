<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loc_state".
 *
 * @property int $state_id
 * @property string $name
 * @property string|null $short_name
 * @property int $country_id
 */
class LocState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loc_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['short_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'state_id' => 'State ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
            'country_id' => 'Country ID',
        ];
    }
}
