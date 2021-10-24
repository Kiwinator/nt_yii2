<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loc_country".
 *
 * @property int $country_id
 * @property string $name
 * @property string|null $short_name
 */
class LocCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loc_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'country_id' => 'Country ID',
            'name' => 'Name',
            'short_name' => 'Short Name',
        ];
    }
}
