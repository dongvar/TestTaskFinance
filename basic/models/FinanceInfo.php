<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "finance_info".
 *
 * @property int $iin_bin
 * @property string|null $name_ru
 * @property float|null $total_arrear
 * @property float|null $total_tax_arrear
 * @property float|null $pension_contribution_arrear
 * @property float|null $social_contribution_arrear
 * @property float|null $social_health_insurance_arrear
 */
class FinanceInfo extends \yii\db\ActiveRecord
{
    public $tax_org_info;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'finance_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iin_bin'], 'required'],
            [['iin_bin'], 'default', 'value' => null],
            [['iin_bin'], 'integer'],
            [['total_arrear', 'total_tax_arrear', 'pension_contribution_arrear', 'social_contribution_arrear', 'social_health_insurance_arrear'], 'number'],
            [['name_ru'], 'string', 'max' => 1024],
            [['iin_bin'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iin_bin' => 'ИИН/БИН налогоплательщика',
            'name_ru' => 'Налогоплательщик',
            'total_arrear' => 'Всего задолженности (тенге)',
            'total_tax_arrear' => 'Итого задолженности в бюджет',
            'pension_contribution_arrear' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'social_contribution_arrear' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'social_health_insurance_arrear' => 'Задолженность по социальным отчислениям',
        ];
    }
    
    /*
    * Инициализация модели данными из массива
    */    
    public function initAttributes($data)
    {
        $this->attributes = [
                                'iin_bin' => $data['iinBin'],
                                'name_ru' => $data['nameRu'],
                                'total_arrear' => $data['totalArrear'],
                                'total_tax_arrear' => $data['totalTaxArrear'],
                                'pension_contribution_arrear' => $data['pensionContributionArrear'],
                                'social_contribution_arrear' => $data['socialContributionArrear'],
                                'social_health_insurance_arrear' => $data['socialHealthInsuranceArrear'],
                            ];
    }
}
