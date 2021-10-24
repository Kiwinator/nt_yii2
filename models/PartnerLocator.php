<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partner_locator".
 *
 * @property int $id
 * @property string $company
 * @property int $status
 * @property string|null $logo
 * @property string|null $address
 * @property string|null $website
 * @property string|null $phone
 * @property string|null $countries_covered
 * @property string|null $states_covered
 */
class PartnerLocator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner_locator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company', 'status'], 'required'],
            [['status'], 'integer'],
            [['countries_covered', 'states_covered'], 'string'],
            [['company', 'logo', 'address', 'website'], 'string', 'max' => 250],
            [['phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company' => 'Company',
            'status' => 'Status',
            'logo' => 'Logo',
            'address' => 'Address',
            'website' => 'Website',
            'phone' => 'Phone',
            'countries_covered' => 'Countries Covered',
            'states_covered' => 'States Covered',
        ];
    }
}
