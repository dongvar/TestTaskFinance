<?php

namespace app\models;

use yii\base\Model;

class RequestForm extends Model
{
    public $iinBin;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iinBin'], 'required'],
            [['iinBin'], 'integer'],
            [['iinBin'], 'string', 'length' => 12],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iinBin' => 'ИИН/БИН',
        ];
    }
}