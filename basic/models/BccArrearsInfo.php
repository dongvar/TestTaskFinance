<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bcc_arrears_info".
 *
 * @property int $id
 * @property int $tax_payer_info_id
 * @property int|null $bcc
 * @property string|null $bcc_name_ru
 * @property float|null $tax_arrear
 * @property float|null $poena_arrear
 * @property float|null $percent_arrear
 * @property float|null $fine_arrear
 * @property float|null $total_arrear
 */
class BccArrearsInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bcc_arrears_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_payer_info_id'], 'required'],
            [['tax_payer_info_id', 'bcc'], 'default', 'value' => null],
            [['tax_payer_info_id', 'bcc'], 'integer'],
            [['tax_arrear', 'poena_arrear', 'percent_arrear', 'fine_arrear', 'total_arrear'], 'number'],
            [['bcc_name_ru'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tax_payer_info_id' => 'Tax Payer Info ID',
            'bcc' => 'КБК',
            'bcc_name_ru' => 'Bcc Name Ru',
            'tax_arrear' => 'Задолженность по платежам, учет по которым ведется в органах государственных доходов',
            'poena_arrear' => 'Задолженность по сумме пени',
            'percent_arrear' => 'Задолженность по сумме процентов',
            'fine_arrear' => 'Задолженность по сумме штрафа',
            'total_arrear' => 'Всего задолженности',
        ];
    }
}
