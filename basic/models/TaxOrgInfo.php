<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax_org_info".
 *
 * @property int $id
 * @property int $iin_bin
 * @property string|null $name_ru
 * @property int|null $char_code
 * @property float|null $total_arrear
 * @property float|null $total_tax_arrear
 * @property float|null $pension_contribution_arrear
 * @property float|null $social_contribution_arrear
 * @property float|null $social_health_insurance_arrear
 * @property int|null $report_acrual_date
 */
class TaxOrgInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_org_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iin_bin'], 'required'],
            [['iin_bin', 'char_code', 'report_acrual_date'], 'default', 'value' => null],
            [['iin_bin', 'char_code', 'report_acrual_date'], 'integer'],
            [['total_arrear', 'total_tax_arrear', 'pension_contribution_arrear', 'social_contribution_arrear', 'social_health_insurance_arrear'], 'number'],
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
            'iin_bin' => 'ИИН/БИН налогоплательщика',
            'name_ru' => 'Орган государственных доходов',
            'char_code' => 'Код ОГД',
            'total_arrear' => 'Всего задолженности',
            'total_tax_arrear' => 'Итого задолженности в бюджет',
            'pension_contribution_arrear' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'social_contribution_arrear' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'social_health_insurance_arrear' => 'Задолженность по социальным отчислениям',
            'report_acrual_date' => 'По состоянию на',
        ];
    }
}