<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax_payer_info".
 *
 * @property int $id
 * @property int $tax_org_info_id
 * @property int $iin_bin
 * @property string|null $name_ru
 * @property float|null $total_arrear
 */
class TaxPayerInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_payer_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_org_info_id', 'iin_bin'], 'required'],
            [['tax_org_info_id', 'iin_bin'], 'default', 'value' => null],
            [['tax_org_info_id', 'iin_bin'], 'integer'],
            [['total_arrear'], 'number'],
            [['name_ru'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_org_info_id' => 'Tax Org Info ID',
            'iin_bin' => 'ИИН/БИН налогоплательщика',
            'name_ru' => 'Налогоплательщик',
            'total_arrear' => 'Всего задолженности',
        ];
    }
}